<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\Result;
use App\Logic\SequenceAnalyzer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnalyzerController extends AbstractController
{
    /**
     * Возвращает результат согласно правилу, из аргументов, предоставленных авторизованным пользователем
     * @param  Request $request
     * @param  EntityManagerInterface $entityManager
     * @param  UserRepository $userRepository
     * @return JsonResponse
     * @throw  \Exception
     * @Route("/setseparator", name="array_separator", methods={"POST"})
     */
    public function setSeparator(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        if($request->headers->get('Content-Type') != 'application/json') {
            return new JsonResponse(['error' => 'Ожидаемый Content-Type - application/json'], 415);
        }
        
        $data = json_decode($request->getContent());
        try{

            if($data === null) {
                throw new \Exception('Отсутствуют поля, либо формат данных отличный от JSON');
            }
            // Проверка авторизации
            $user = $userRepository->findOneBy([
                'name'      => $data->user_name,
                'auth_code' => $data->auth_code
            ]);

            if($user === null) {
                throw new \Exception('Пользователь с таким именем и/или кодом авторизации не существует!');
            }
            // Получаем индекс элемента массива, согласно правилу
            $analyzer = new SequenceAnalyzer($data->sequence);
            $index    = $analyzer->getIndex($data->number);
            // Запись результата в объект
            $result = new Result();
            $result->setArr($data->sequence)
                   ->setNum($data->number)
                   ->setIndex($index)
                   ->setUserId($user);
            // Сохранение результата в базу
            $entityManager->persist($result);
            $entityManager->flush();

            return new JsonResponse(['index' => $index], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
