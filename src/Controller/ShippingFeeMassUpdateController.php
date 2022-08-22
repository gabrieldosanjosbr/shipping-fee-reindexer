<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Component\Form\Form;
use App\Service\ShippingFeeMassUpdateFile;
use Symfony\Component\Form\FormInterface;
use App\Form\ShippingFeeMassUpdateFileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class ShippingFeeMassUpdateController extends AbstractController
{
    /**
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function index(): Response
    {
        return $this->render('shipping_fee.html.twig');
    }

    /**
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param MessageBusInterface $bus
     * @return Response
     */
    public function uploadFile(
        Request $request,
        FileUploader $fileUploader,
        MessageBusInterface $bus
    ): Response {
        $formUpload = $this->createForm(ShippingFeeMassUpdateFileType::class);
        $formUpload->handleRequest($request);

        if ($formUpload->isSubmitted() && $formUpload->isValid()) {
            /** @var UploadedFile $file */
            $file = $formUpload->get('file')->getData();
            $fileName = $fileUploader->upload($file);

            $bus->dispatch(new ShippingFeeMassUpdateFile($fileUploader->getTargetDirectory() . '/' . $fileName));

            return $this->json([
                'message' => 'File uploaded successfully',
                'uniqid'  => $fileName
            ]);
        }

        return $this->json($this->getFormErrors($formUpload), 400);
    }

    /**
     * @param Form $form
     * @return array
     */
    protected function getFormErrors(FormInterface $form): array
    {
        $errors = array();

        // Fields
        foreach ($form as $child /** @var Form $child */) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors['errors'][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}
