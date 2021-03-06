<?php
/**
 * Cybage CodExtracharge
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available on the World Wide Web at:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to access it on the World Wide Web, please send an email
 * To: Support_ecom@cybage.com.  We will send you a copy of the source file.
 *
 * @category  Apply_Extra_Charge_On_COD_Payment_Method
 * @package   Cybage_CodExtracharge
 * @author    Cybage Software Pvt. Ltd. <Support_ecom@cybage.com>
 * @copyright 1995-2019 Cybage Software Pvt. Ltd., India
 *            http://www.cybage.com/pages/centers-of-excellence/ecommerce/ecommerce.aspx
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Cybage\CodExtracharge\Model;

use \Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Cybage\CodExtracharge\Api\CashondeliveryCartInterface;

/**
 * CashondeliveryCart
 *
 * @category  Class
 * @package   Cybage_CodExtracharge
 * @author    Cybage Software Pvt. Ltd. <Support_ecom@cybage.com>
 * @copyright 1995-2019 Cybage Software Pvt. Ltd., India
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version   Release: 1.0.0
 * @link      http://www.cybage.com/pages/centers-of-excellence/ecommerce/ecommerce.aspx
 */

class CashondeliveryCart implements CashondeliveryCartInterface
{
    protected $priceCurrencyInterface;
    protected $checkoutSession;
    protected $quote = null;

    /**
     * Constructor
     *
     * @param PriceCurrencyInterface $priceCurrencyInterface
     * @param CheckoutSession        $checkoutSession
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrencyInterface,
        CheckoutSession $checkoutSession
    ) {
        $this->priceCurrencyInterface = $priceCurrencyInterface;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Get current quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (is_null($this->quote)) {
            $this->quote = $this->checkoutSession->getQuote();
            $this->quote->collectTotals();
        }
        return $this->quote;
    }

    /**
     * Get amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->getQuote()->getCybCodAmount();
    }

    /**
     * Get base amount
     *
     * @return double
     */
    public function getBaseAmount()
    {
        return $this->getQuote()->getBaseCybCodAmount();
    }

    /**
     * Get additional fee label
     *
     * @return string
     */
    public function getFeeLabel()
    {
        $amount = $this->getAmount();
        // Need not display label if extra fee is zero
        if ($amount == 0 ) {
            return '';
        }

        return __(
            'You will be charged by an extra fee of %1 ',
            [$this->priceCurrencyInterface->format($amount)]
        );
    }
}
