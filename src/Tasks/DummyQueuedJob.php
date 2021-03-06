<?php

namespace Symbiote\QueuedJobs\Tasks;

use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;

class DummyQueuedJob extends AbstractQueuedJob implements QueuedJob
{
    /**
     * @param int $number
     */
    public function __construct($number = 0)
    {
        if ($number) {
            $this->startNumber = $number;
            $this->totalSteps = $this->startNumber;
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Some test job for ' . $this->startNumber . ' seconds';
    }

    /**
     * @return string
     */
    public function getJobType()
    {
        return QueuedJob::QUEUED;
    }

    public function setup()
    {
        // just demonstrating how to get a job going...
        $this->totalSteps = $this->startNumber;
        $this->times = array();
    }

    public function process()
    {
        $times = $this->times;
        // needed due to quirks with __set
        $times[] = date('Y-m-d H:i:s');
        $this->times = $times;

        $this->addMessage('Updated time to ' . date('Y-m-d H:i:s'));
        sleep(1);

        // make sure we're incrementing
        $this->currentStep++;

        // if ($this->currentStep > 1) {
        //     $this->currentStep = 1;
        // }

        // and checking whether we're complete
        if ($this->currentStep >= $this->totalSteps) {
            $this->isComplete = true;
        }
    }
}
