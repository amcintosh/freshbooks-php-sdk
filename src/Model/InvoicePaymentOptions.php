<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

/**
 * In FreshBooks, invoices can be paid online via a variety of payment gateways
 * setup on the sender’s account. In order for this to be available on an invoice,
 * the online payments must be set up through a separate call after the invoice has
 * been created.
 *
 * While default payment options exist, they are not automatically applied to new
 * invoices and must be retrieved and added manually.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/online-payments
 */
class InvoicePaymentOptions implements DataModel
{
    public const RESPONSE_FIELD = 'payment_options';

    /**
     * @var string invoice_id of the connected invoice.
     *
     * _Note_: The API returns this as `entity_id`.
     */
    public ?string $entityId;

    /**
     * @var string Eg. “invoices”.
     */
    public ?string $entityType;

    /**
     * @var string Payment gateway name.
     */
    public ?string $gatewayName;

    /**
     * @var bool If the invoice can accept credit cards.
     */
    public ?bool $hasCreditCard;

    /**
     * @var bool If the invoice can accept ACH bank transfers.
     */
    public ?bool $hasAchTransfer;

    public ?bool $hasAcssDebit;

    public ?bool $hasBacsDebit;

    public ?bool $hasSepaDebit;

    public ?bool $hasPaypalSmartCheckout;

    /**
     * @var bool If the client can use the gateway to pay part
     * of the invoice or only the full amount.
     */
    public ?bool $allowPartialPayments;

    public function __construct(array $data = [])
    {
        $this->entityId = isset($data['entity_id']) ? (string) $data['entity_id'] : null;
        $this->entityType = $data['entity_type'] ?? null;
        $this->gatewayName = $data['gateway_name'] ?? null;
        $this->hasCreditCard = $data['has_credit_card'] ?? null;
        $this->hasAchTransfer = $data['has_ach_transfer'] ?? null;
        $this->hasAcssDebit = $data['has_acss_debit'] ?? null;
        $this->hasBacsDebit = $data['has_bacs_debit'] ?? null;
        $this->hasSepaDebit = $data['has_sepa_debit'] ?? null;
        $this->hasPaypalSmartCheckout = $data['has_paypal_smart_checkout'] ?? null;
        $this->allowPartialPayments = $data['allow_partial_payments'] ?? null;
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = array();
        Util::convertContent($data, 'entity_id', $this->entityId);
        Util::convertContent($data, 'entity_type', $this->entityType);
        Util::convertContent($data, 'gateway_name', $this->gatewayName);
        Util::convertContent($data, 'has_credit_card', $this->hasCreditCard);
        Util::convertContent($data, 'has_ach_transfer', $this->hasAchTransfer);
        Util::convertContent($data, 'allow_partial_payments', $this->allowPartialPayments);
        return $data;
    }
}
