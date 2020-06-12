<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 20:10:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-12 10:24:05
 */

namespace Magepow\Theme\Model\System\Config;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            0 => 'Physical',
            1 => 'Virtual',
            2 => 'Staging'
        ];
    }
}
