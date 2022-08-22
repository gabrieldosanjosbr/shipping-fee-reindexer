<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\ShippingFee;
use App\Repository\ShippingFeeRepository;
use App\Service\ShippingFeeMassUpdateFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ShippingFeeMassUpdateFileHandler
{
    private EntityManagerInterface $entityManager;
    private ShippingFeeRepository $shippingFeeRepository;

    public function __construct(EntityManagerInterface $entityManager, ShippingFeeRepository $shippingFeeRepository)
    {
        $this->entityManager = $entityManager;
        $this->shippingFeeRepository = $shippingFeeRepository;
    }

    public function __invoke(ShippingFeeMassUpdateFile $shippingFeeMassUpdateFile): void
    {
        $handle = fopen($shippingFeeMassUpdateFile->getFilePath(), "r");

        $row = -1;
        $batchSize = 5000;
        if (($handle) !== FALSE) {
            while (($data = fgetcsv($handle, $batchSize, ";")) !== FALSE) {
                $row++;
                if ($row == 0) {
                    continue;
                }

                $cents = filter_var($data[4], FILTER_SANITIZE_NUMBER_INT);
                $price = floatval($cents / 100);

                $shippingFee = $this->shippingFeeRepository->find($row) ?: new ShippingFee();
                $shippingFee->setFromPostcode($data[0]);
                $shippingFee->setToPostcode($data[1]);
                $shippingFee->setFromWeight($data[2]);
                $shippingFee->setToWeight($data[3]);
                $shippingFee->setCost($price);

                $this->entityManager->persist($shippingFee);

                if (($row % $batchSize) === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }

            fclose($handle);

            $this->entityManager->flush();
            $this->entityManager->clear();
        }


        unlink($shippingFeeMassUpdateFile->getFilePath());
    }
}
