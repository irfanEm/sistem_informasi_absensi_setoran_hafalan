<?php

namespace IRFANM\SIASHAF\Repository;

use IRFANM\SIASHAF\Config\Database;
use PHPUnit\Framework\TestCase;

class MasterHafalanRepositoryTest extends TestCase
{
    private MasterHafalanRepository $masterHafalanRepository;

    public function setUp(): void
    {
        $this->masterHafalanRepository = new MasterHafalanRepository(Database::getConn());

        $this->masterHafalanRepository->deleteAllPermanently();
    }

    public function testGetAllHafalan()
    {

    }

    public function testUpdateMasterHafalan()
    {

    }
}