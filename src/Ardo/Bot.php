<?php
namespace WeCamp\Ardo;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use WeCamp\Ardo\Plugin\InputInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;
use WeCamp\Ardo\Plugin\PluginInterface;

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
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->input = [];
        $this->output = [];
        $this->logger = $logger;
    }

    /**
     * @param InputInterface $input
     */
    public function registerInput(InputInterface $input)
    {
        \array_push($this->input, $input);
        $this->addLoggerToPlugin($input);
    }

    /**
     * @param OutputInterface $output
     */
    public function registerOutput(OutputInterface $output)
    {
        \array_push($this->output, $output);
        $this->addLoggerToPlugin($output);
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

    /**
     * @param PluginInterface $plugin
     */
    private function addLoggerToPlugin(PluginInterface $plugin)
    {
        if ($plugin instanceof LoggerAwareInterface) {
            $plugin->setLogger($this->logger);
        }
    }
}
