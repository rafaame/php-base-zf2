<?php

namespace ApplicationTest\Util;

use AndreattaTest\Util\Util,

	Application\Entity;

class DataGenerator
{

	public static function arrayRandom($array)
	{

		return $array[array_rand($array)];

	}

	public static function generateTestingData($objectManager, $count = [])
	{

		$defaultCount =
		[

			'customers' => isset($count['*']) ? $count['*'] : 5,
			'addresses' => isset($count['*']) ? $count['*'] : 3,
			'phones' => isset($count['*']) ? $count['*'] : 3,
			'orders' => isset($count['*']) ? $count['*'] : 10,
			'orderProducts' => isset($count['*']) ? $count['*'] : 5,
			'neighborhoods' => isset($count['*']) ? $count['*'] : 10,
			'products' => isset($count['*']) ? $count['*'] : 50,

		];

		$count = array_merge($defaultCount, $count);

		$numberCustomers = $count['customers'];
		$numberAddresses = $count['addresses'];
		$numberPhones = $count['phones'];
		$numberOrders = $count['orders'];
		$numberOrderProducts = $count['orderProducts'];
		$numberNeighborhoods = $count['neighborhoods'];
		$numberProducts = $count['products'];

		$todayStart = new \Datetime('now');
		$todayStart = $todayStart->setTime(19, 00);
		
		$todayEnd = new \Datetime('now');
		$todayEnd = $todayEnd->setTime(23, 00);

		$neighborhoods = [];
		for($i = 0; $i < $numberNeighborhoods; $i++)
		{

			$neighborhood = new Entity\Neighborhood();
			$neighborhood
				->setName(Util::randomString())
				->setShippingPrice(Util::randomFloat());

			$neighborhoods[] = $neighborhood;

		}

		$tills = [];
		for($i = 0; $i < $numberOrders; $i++) //Yes, it's $numberOrders
		{

			$dateCreated = clone $todayEnd;

			$till = new Entity\Till();
			$till
				->setDateCreated( $dateCreated->add(new \DateInterval('P' . ($i + 1) . 'D')) );

			$tills[] = $till;

		}

		$products = [];
		for($i = 0; $i < $numberProducts; $i++)
		{

			//@FIXME: test product image

			$product = new Entity\Product();
			$product
				->setLabel(Util::randomString())
				->setExtendedLabel(Util::randomString())
				->setDescription(Util::randomString())
				//->setImage()
				->setPrice(Util::randomFloat(1, 100))
				->setActive(Util::randomBool())
				->setOrder($i);

			$products[] = $product;

		}

		$customers = [];
		for($i = 0; $i < $numberCustomers; $i++)
		{

			$customer = [];
			$customer['entity'] = new Entity\Customer();

			$addresses = [];
			for($j = 0; $j < $numberAddresses; $j++)
			{

				$neighborhood = self::arrayRandom($neighborhoods);

				$address = new Entity\StreetAddress();
				$address
					->setCustomer($customer['entity'])
					->setNeighborhood($neighborhood)
					->setStreet(Util::randomString())
					->setNumber(Util::randomInt())
					->setComplement(Util::randomString())
					->setZipcode(Util::randomInt())
					->setReference(Util::randomString());

				$neighborhood->getAddresses()->add($address);

				$addresses[] = $address;

			}

			$customer['addresses'] = $addresses;

			$phones = [];
			for($j = 0; $j < $numberPhones; $j++)
			{

				$phone = new Entity\PhoneNumber();
				$phone
					->setCustomer($customer['entity'])
					->setCountryCode(Util::randomInt())
					->setAreaCode(Util::randomInt())
					->setNumber(Util::randomInt())
					->setMobile(Util::randomBool());

				$phones[] = $phone;

			}

			$customer['phones'] = $phones;

			$orders = [];
			$customerOrders = [];
			for($j = 0; $j < $numberOrders; $j++)
			{

				$order['entity'] = new Entity\Order();

				$address = self::arrayRandom(array_merge($addresses, [null]));
				$period = self::arrayRandom(array_keys(Entity\Order::periodAvailable()));
				$price = 0;
				$dateCreated = clone $todayStart;
				$dateCreated->add(new \DateInterval('P' . ($j + 1) . 'DT' . ($i + 1) . 'M'));
				$dateCookingStart = clone $order['entity']->getDateCreated();
				$dateCookingStart = self::arrayRandom([$dateCookingStart->add(new \DateInterval('PT' . Util::randomInt(1, 3) . 'H' . Util::randomInt(1, 59) . 'M')), null]);
				$dateShippingStart = clone $order['entity']->getDateCreated();
				$dateShippingStart = $dateCookingStart ? self::arrayRandom([$dateShippingStart->add(new \DateInterval('PT' . Util::randomInt(1, 3) . 'H' . Util::randomInt(1, 59) . 'M')), null]) : null;
				$till = null;
				$active = Util::randomBool();
				$approved = $active ? Util::randomBool() : true;
				$paid = $active ? ($approved ? Util::randomBool() : false) : true;

				$orderProducts = [];
				for($k = 0; $k < $numberOrderProducts; $k++)
				{

					$product = self::arrayRandom($products);

					//@FIXME: test properties

					$orderProduct = new Entity\OrderProduct();
					$orderProduct
						->setOrder($order['entity'])
						->setProduct($product)
						->setQuantity(Util::randomInt(1, 5))
						->setUnitPrice($product->getPrice());
						//->setProperties($properties);

					$orderProducts[] = $orderProduct;

					$price += $orderProduct->getQuantity() * $product->getPrice();

				}

				$order['products'] = $orderProducts;

				$statuses = [];
				$statusDateOccured = clone $dateCreated;
				$availableStatuses = array_keys(Entity\OrderStatus::statusAvailable());
				$numberStatuses = $active ? Util::randomInt(1, count($availableStatuses)) : count($availableStatuses);
				for($k = 0; $k < $numberStatuses; $k++)
				{

					$statusDateOccured->add(new \DateInterval('PT' . $k . 'M'));

					$status = new Entity\OrderStatus();
					$status
						->setOrder($order['entity'])
						->setStatus( $availableStatuses[$k] )
						->setDateOccured(clone $statusDateOccured);

					$statuses[] = $status;

				}

				$order['statuses'] = $statuses;

				if(!$active)
				{

					foreach($tills as $currentTill)
						if($currentTill->getDateCreated()->getTimestamp() > $dateCreated->getTimestamp())
						{

							$till = $currentTill;
							$till
								->setPeriod($period);

							break;

						}

					$till
						->setNumberOrders( $till->getNumberOrders() + 1 )
						->setAmount( $till->getAmount() + $price )
						->getOrders()->add($order['entity']);

				}

				$order['entity']
					->setCustomer($customer['entity'])
					->setAddress($address)
					->setOrderProducts($orderProducts)
					->setStatuses($statuses)
					->setTill($till)
					->setOrderNumber($i + 1)
					->setPeriod($period)
					->setPrice($price)
					->setChange($price + Util::randomFloat())
					->setPaymentMethod( self::arrayRandom(array_keys(Entity\Order::paymentMethodAvailable())) )
					->setShippingPrice(Util::randomFloat())
					->setDateCreated($dateCreated)
					->setDateCookingStart($dateCookingStart)
					->setDateCookingEnd( $dateCookingStart ? $dateCookingStart->add(new \DateInterval('PT' . Util::randomInt(1, 2) . 'H' . Util::randomInt(1, 59) . 'M')) : null )
					->setDateShippingStart($dateShippingStart)
					->setDateShippingEnd( $dateShippingStart ? $dateShippingStart->add(new \DateInterval('PT' . Util::randomInt(1, 2) . 'H' . Util::randomInt(1, 59) . 'M')) : null )
					->setNotes( self::arrayRandom([Util::randomString(), '']) )
					->setNumberChopsticks(Util::randomInt(0, 10))
					->setSendSoySauce(Util::randomBool())
					->setSendWasabi(Util::randomBool())
					->setSendGari(Util::randomBool())
					->setApproved($approved)
					->setActive($active)
					->setPaid($paid);

				$orders[] = $order;
				$customerOrders[] = $order['entity'];
				$lastOrder = $order['entity'];

			}

			$customer['orders'] = $orders;

			$customer['entity']
			    ->setName(Util::randomString())
			    ->setEmail(Util::randomEmail())
			    ->setPassword(Util::randomString(), true)
			    ->setAddresses($addresses)
			    ->setPhones($phones)
			    ->setOrders($customerOrders)
			    ->setLastOrder($lastOrder)
			    ->setDateLastOrder($lastOrder->getDateCreated())
			    ->setActive(Util::randomBool())
			    ->setNewsletter(Util::randomBool())
			    ->setDebugger(Util::randomBool());

			$customers[] = $customer;

		}

		foreach($neighborhoods as $neighborhood)
			$objectManager->persist($neighborhood);

		foreach($tills as $till)
			$objectManager->persist($till);

		foreach($products as $product)
			$objectManager->persist($product);

		foreach($customers as $customer)
		{

			foreach($customer['addresses'] as $address)
				$objectManager->persist($address);

			foreach($customer['phones'] as $phone)
				$objectManager->persist($phone);

			foreach($customer['orders'] as $order)
			{

				foreach($order['products'] as $orderProduct)
					$objectManager->persist($orderProduct);

				foreach($order['statuses'] as $status)
					$objectManager->persist($status);

				$objectManager->persist($order['entity']);

			}

			$objectManager->persist($customer['entity']);

		}

		$objectManager->flush();

		return
		[

			'neighborhoods' => $neighborhoods,
			'tills' => $tills,
			'products' => $products,
			'customers' => $customers,

		];

	}

}