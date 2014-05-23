<?php
namespace AndreattaTest\Model;

use AndreattaTest\Framework\TestCase,
    AndreattaTest\Util\Util,
    AndreattaTest\Mock\Entity\Test as EntityMock,
    AndreattaTest\Mock\Model\Test as ModelMock;

class BaseTest extends TestCase
{

    public function testPaginator()
    {

        $serviceLocator = $this->getServiceManager();
        $objectManager = $this->getObjectManager();

        $entities = [];
        $countPerPage = 10;
        $pages = 5;
        
        for($i = 0; $i < $pages; $i++)
        {

            $entities[$i] = [];

            for($j = 0; $j < $countPerPage; $j++)
            {

                $entity = new EntityMock();
                $entity->test = Util::randomString();
                $objectManager->persist($entity);

                $entities[$i][$j] = $entity;

            }

        }

        $objectManager->flush();

        $model = new ModelMock($serviceLocator, $objectManager);
        $paginator = $model->paginator($countPerPage);

        $this->assertSame($pages, $paginator->count(), 'Number of pages does not match.');
        $this->assertSame($pages * $countPerPage, $paginator->getTotalItemCount(), 'Total item count does not match.');
        $this->assertSame(1, $paginator->getCurrentPageNumber(), 'Not starting on the first page.');

        for($i = 0; $i < $pages; $i++)
        {

            $paginator->setCurrentPageNumber($i + 1);
            $this->assertSame($i + 1, $paginator->getCurrentPageNumber());

            $items = $paginator->getCurrentItems();

            $j = 0;
            foreach($items as $item)
            {

                if($j >= $countPerPage)
                    $this->fail('Page ' . ($j + 1) . 'has more items than the allowed items per page (' . $countPerPage . ').');

                $this->assertInstanceOf('AndreattaTest\Mock\Entity\Mock', $item);
                $this->assertSame($entities[$i][$j], $item);

                $j++;

            }

        }

    }

}