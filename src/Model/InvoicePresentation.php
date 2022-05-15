<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Invoice Presentations are used to style an invoice including font, colors, and logos.
 *
 * By default, when a new invoice is created, it automatically uses the presentation style
 * of the most recently created invoice (including logos, colors, and fonts). If you wish
 * to change the style of an invoice via the API, or if the user has not styled their
 * invoices (such as in a brand new account), then you can pass a <code>presentation</code>
 * object as part of the invoice call. Subsequent invoices will use this style until an
 * invoice is created with a new style. If you do not wish for an invoice to use any styles,
 * you can include <code>use_default_presentation: false</code> in the invoice call.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoice_presentation_attachments
 */
class InvoicePresentation extends DataTransferObject
{
    /**
     * @var int The unique identifier of the invoice this presentation applies to.
     */
    #[MapFrom('invoiceid')]
    #[MapTo('invoiceid')]
    public ?int $invoiceId;

    /**
     * @var string Override business date format for this invoice.
     *
     * string format of: “mm/dd/yyyy”, “dd/mm/yyyy”, or “yyyy-mm-dd”
     */
    #[MapFrom('date_format')]
    #[MapTo('date_format')]
    public ?string $dateFormat;

    /**
     * @var string The invoice banner image for "modern" invoices.
     *
     * “/uploads/images/<JWT_TOKEN_FROM_IMAGE_UPLOAD>”
     */
    #[MapFrom('image_banner_src')]
    #[MapTo('image_banner_src')]
    public ?string $imageBannerSrc;

    /**
     * @var string The invoice logo image.
     *
     * “/uploads/images/<JWT_TOKEN_FROM_IMAGE_UPLOAD>”
     */
    #[MapFrom('image_logo_src')]
    #[MapTo('image_logo_src')]
    public ?string $imageLogoSrc;

    /**
     * @var string Which invoice font is in use.
     *
     * “modern” or “classic”
     */
    #[MapFrom('theme_font_name')]
    #[MapTo('theme_font_name')]
    public ?string $themeFontName;

    /**
     * @var string Which invoice template is in use.
     *
     * “simple, “modern”, or “classic”
     */
    #[MapFrom('theme_layout')]
    #[MapTo('theme_layout')]
    public ?string $themeLayout;

    /**
     * @var string Primary highlight colour for the invoice.
     *
     * eg. “#345beb”
     */
    #[MapFrom('theme_primary_color')]
    #[MapTo('theme_primary_color')]
    public ?string $themePrimaryColor;
}
