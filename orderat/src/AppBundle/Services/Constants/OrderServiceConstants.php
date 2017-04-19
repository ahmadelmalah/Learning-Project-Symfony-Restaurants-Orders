<?php
use AppBundle\Entity\State;

//Number of items per page
define('ORDERS_PER_PAGE', 5);

define('CURRENT_ORDERS_ROUTES_ARRAY', [
    'active',
    'ajax-active',
    'apiActiveOrders'
]);
define('CURRENT_ORDERS_STATES_ARRAY', [
    State::ACTIVE,
    State::READY,
    State::WAITING
]);

define('HISTORY_ORDERS_ROUTES_ARRAY', [
    'archive',
    'ajax-archive',
    'apiArchiveOrders'
]);
define('HISTORY_ORDERS_STATES_ARRAY', [
    State::DELIVERED,
    State::COMPLETE
]);
