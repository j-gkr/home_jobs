<?php


namespace App\Manager;


use App\Entity\User;
use App\Form\Request\UserRequest;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;

/**
 * Class UserManager
 *
 * @package App\Manager
 */
class UserManager
{
    /**
     * @var FilesystemInterface
     */
    private $usersStorage;

    /**
     * UserManager constructor.
     *
     * @param FilesystemInterface $usersStorage
     */
    public function __construct(FilesystemInterface $usersStorage)
    {
        $this->usersStorage = $usersStorage;
    }

    /**
     * @param UserRequest $userRequest
     * @param User $user
     * @return bool
     */
    public function uploadProfileImage(UserRequest $userRequest, User $user): bool
    {
        // upload file
        $uploadedFile = $userRequest->getAvatarFile();
        $stream = fopen($uploadedFile->getRealPath(), 'r+');

        try {
            // delete old file
            $this->usersStorage->delete($user->getAvatarFile());
        } catch (FileNotFoundException $e) {
        }

        try {
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $uploadedFile->getClientOriginalName());
            $newFilename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $uploadedFile->guessExtension());
            $this->usersStorage->writeStream(sprintf('/%s', $newFilename), $stream);
            $user->setAvatarFile($newFilename);
        } catch (FileExistsException $e) {
            return false;
        } finally {
            fclose($stream);
        }

        return true;
    }
}