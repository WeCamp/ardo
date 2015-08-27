<?php
namespace WeCamp\Ardo;

use WeCamp\Ardo\Plugin\InputInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;

class Bot
{
    /**
     * @var InputInterface[]
     */
    private $input;

    /**
     * @var OutputInterface[]
     */
    private $output;

    /**
     *
     */
    public function __construct()
    {
        $this->input = [];
        $this->output = [];
    }

    /**
     * @param InputInterface $input
     */
    public function registerInput(InputInterface $input)
    {
        \array_push($this->input, $input);

    }

    /**
     * @param OutputInterface $output
     */
    public function registerOutput(OutputInterface $output)
    {
        \array_push($this->output, $output);
    }

    /**
     *
     */
    public function tick()
    {
        foreach($this->input as $input) {
            $incomingMessage = $input->poll();
            $this->sendMessageToOutput($incomingMessage);
        }
    }

    /**
     * @param MessageInterface $message
     * @return array
     */
    private function sendMessageToOutput(MessageInterface $message)
    {
        foreach($this->output as $output) {
            $output->handleMessage($message);
        }
    }
}
