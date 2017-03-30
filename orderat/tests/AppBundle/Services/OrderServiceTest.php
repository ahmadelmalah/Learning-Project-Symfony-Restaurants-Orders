<?php
use PHPUnit\Framework\TestCase;
use AppBundle\Services\OrderService;
/**
 * @covers Email
 */
final class OrderServiceTest extends TestCase
{
    public function testFilterQueryConverter()
    {
        $this->assertEquals(
            array('state' => array(1, 2, 3)),
            OrderService::getQueryFilterFromUrlFilter('active')
        );
        $this->assertEquals(
            array('state' => array(4, 5)),
            OrderService::getQueryFilterFromUrlFilter('archive')
        );
    }



}
