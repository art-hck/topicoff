<?php

namespace ProfileBundle\Service;

use AccountBundle\Entity\Account;
use AuthBundle\Service\AuthService;
use ProfileBundle\Entity\Profile;
use ProfileBundle\Entity\Profile\Gender\NoneGender;
use ProfileBundle\Event\ProfileCreatedEvent;
use ProfileBundle\Repository\ProfileRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProfileService
//    implements ContainerAwareInterface
{
    private $profileRepository;
    private $authService;
    private $eventDispatcher;
    private $profilesLimit;

    /** @var  ContainerInterface */
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct(
        ProfileRepository $profileRepository,
        AuthService $authService,
        EventDispatcherInterface $eventDispatcher,
        int $profilesLimit
    ) {
        $this->profileRepository = $profileRepository;
        $this->authService = $authService;
        $this->eventDispatcher = $eventDispatcher;
        $this->profilesLimit = $profilesLimit;
    }

    public function getById($id): Profile
    {
        return $this->profileRepository->getById($id);
    }

    public function getByAlias($alias): Profile
    {
        return $this->profileRepository->getByAlias($alias);
    }

    public function getByAccountId(int $accountId)
    {
        return $this->profileRepository->getByAccountId($accountId);
    }

    public function createFromArray(array $request, Account $account, bool $persist = false): Profile
    {
        $profile = new Profile();
        $profile->setAccount($account)
            ->setName($request['name'])
            ->setAlias($request['alias'] ?? null)
            ->setGender($request['gender'] ?? new NoneGender())
            ->setBirthDate($request['birth_date'] ?? null)
        ;

        if ($persist) {
            $this->create($profile);
        }

        return $profile;
    }

    public function create(Profile $profile): Profile
    {
        $profiles = $this->getByAccountId($profile->getAccount()->getId());

        if (count($profiles) >= $this->profilesLimit) {
            throw new AccessDeniedHttpException(
                sprintf("The maximum number of profiles allowed in your account is %s", $this->profilesLimit)
            );
        }

        $this->profileRepository->save($profile);
        $this->eventDispatcher->dispatch(ProfileCreatedEvent::NAME, new ProfileCreatedEvent($profile));

        return $profile;
    }

    public function update(Profile $profile) : Profile
    {
        $account = $this->authService->getAccount();

        if($profile->getAccount()->getId() !== $account->getId()) {
            throw new AccessDeniedHttpException("Account has no access for profile changes");
        }

        $this->profileRepository->save($profile);

        return $profile;
    }

    public function uploadAvatar(Profile $profile, UploadedFile $file, $body)
    {
        $path = $this->container->getParameter('profile.avatar.absolute_path');

        dump($path);
//        $imageRes = imagecrop(
//            imagecreatefromjpeg($file->getRealPath()),
//            [
//                'x' => $body['x'],
//                'y' => $body['x'],
//                'width' => $body['width'],
//                'height' => $body['height'],
//            ]
//        );
//
//        $path = $this->container->getParameter('profile.avatar.absolute_path');
//
//        $name = uniqid() .  "." . $file->getClientOriginalExtension();
//        imagejpeg($imageRes, $path . "/"  . $name);
    }

    public function delete(Profile $profile)
    {
        return $this->profileRepository->delete($profile);
    }
}