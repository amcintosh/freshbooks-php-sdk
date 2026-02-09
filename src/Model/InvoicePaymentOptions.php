<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModel;

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
class InvoicePaymentOptions extends DataTransferObject implements DataModel
{
    public const RESPONSE_FIELD = 'payment_options';

    /**
     * @var string invoice_id of the connected invoice.
     *
     * _Note_: The API returns this as `entity_id`.
     */
    #[MapFrom('entity_id')]
    #[MapTo('entity_id')]
    public ?string $entityId;

    /**
     * @var string Eg. “invoices”.
     */
    #[MapFrom('entity_type')]
    #[MapTo('entity_type')]
    public ?string $entityType;

    /**
     * @var string Payment gateway name.
     */
    #[MapFrom('gateway_name')]
    #[MapTo('gateway_name')]
    public ?string $gatewayName;

    /**
     * @var bool If the invoice can accept credit cards.
     */
    #[MapTo('has_credit_card')]
    #[MapFrom('has_credit_card')]
    public ?bool $hasCreditCard;

    /**
     * @var bool If the invoice can accept ACH bank transfers.
     */
    #[MapTo('has_ach_transfer')]
    #[MapFrom('has_ach_transfer')]
    public ?bool $hasAchTransfer;

    #[MapFrom('has_acss_debit')]
    public ?bool $hasAcssDebit;

    #[MapFrom('has_bacs_debit')]
    public ?bool $hasBacsDebit;

    #[MapFrom('has_sepa_debit')]
    public ?bool $hasSepaDebit;

    #[MapFrom('has_paypal_smart_checkout')]
    public ?bool $hasPaypalSmartCheckout;

    /**
     * @var bool If the client can use the gateway to pay part
     * of the invoice or only the full amount.
     */
    #[MapTo('allow_partial_payments')]
    #[MapFrom('allow_partial_payments')]
    public ?bool $allowPartialPayments;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('hasAcssDebit')
            ->except('hasBacsDebit')
            ->except('hasSepaDebit')
            ->except('hasPaypalSmartCheckout')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
