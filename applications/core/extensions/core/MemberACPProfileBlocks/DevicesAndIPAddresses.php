<?php
/**
 * @brief       ACP Member Profile: Devices & IP Addresses Block
 * @author      <a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright   (c) Invision Power Services, Inc.
 * @license     https://www.invisioncommunity.com/legal/standards/
 * @package     Invision Community
 * @since       20 Nov 2017
 */

namespace IPS\core\extensions\core\MemberACPProfileBlocks;

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * @brief ACP Member Profile: Devices & IP Addresses Block
 */
class _DevicesAndIPAddresses extends \IPS\core\MemberACPProfile\Block
{
    /**
     * Get output
     *
     * @return string
     */
    public function output()
    {
        // Returning an empty string to disable front-end effect
        return '';
    }
}
