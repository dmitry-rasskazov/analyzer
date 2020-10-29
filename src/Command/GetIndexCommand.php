<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Result;
use App\Repository\UserRepository;
use App\Logic\SequenceAnalyzer;
use Doctrine\ORM\EntityManagerInterface;

class GetIndexCommand extends Command
{

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->entityManager  = $entityManager;
    }

    protected function configure(): void
    {
        $this->setName('analyzer:get-index')
             ->setDescription('Возвращает индекс элемента заданного массива')
             ->setHelp('Возвращает индекс элемента заданного массива, соответсвующий правилу, либо -1, когда невозможно вычислить')
             ->addArgument('num', InputArgument::REQUIRED, 'Число (целое), применяемое при определении индекса')
             ->addArgument('array', InputArgument::REQUIRED, 'Массив целых чисел. Передаётся в строку через запятую без пробеловю Пример: 1,1,2,3,5')
             ->addArgument('user', InputArgument::OPTIONAL, 'ID пользователя в базе');
             
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sequence = array_map(
            function($value):int {
              return (int) $value;
            },
            explode(',', $input->getArgument('array')));
        $number   = (int) $input->getArgument('num');
        $userId   = (int) $input->getArgument('user');

        $output->writeln([
            'Get Index',
            '========='
        ]);

        try{
            // Получить пользователя по id
            $user = $this->userRepository->findOneBy(['id' => $userId]);
            // Получить индекс элемента разделителя
            $analyzer = new SequenceAnalyzer($sequence);
            $index    = $analyzer->getIndex($number);
            // Подготовить результат к загрузке в базу
            $result = new Result();
            $result->setArr($sequence)
                   ->setNum($number)
                   ->setIndex($index)
                   ->setUserId($user);
            // Загрузить результат в базу
            $this->entityManager->persist($result);
            $this->entityManager->flush();

            $output->writeln('index: ' . $index);
        } catch(\Exception $e) {
            $output->writeln($e->getMessage());
        }

        return 0;
    }
}