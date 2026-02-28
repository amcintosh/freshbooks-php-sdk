<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\DataModel;

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
class InvoicePresentation implements DataModel
{
    /**
     * @var int The unique identifier of the invoice this presentation applies to.
     */
    public ?int $invoiceId;

    /**
     * @var string Override business date format for this invoice.
     *
     * string format of: “mm/dd/yyyy”, “dd/mm/yyyy”, or “yyyy-mm-dd”
     */
    public ?string $dateFormat;

    /**
     * @var string The invoice banner image for "modern" invoices.
     *
     * “/uploads/images/<JWT_TOKEN_FROM_IMAGE_UPLOAD>”
     */
    public ?string $imageBannerSrc;

    /**
     * @var string The invoice logo image.
     *
     * “/uploads/images/<JWT_TOKEN_FROM_IMAGE_UPLOAD>”
     */
    public ?string $imageLogoSrc;

    /**
     * @var string Which invoice font is in use.
     *
     * “modern” or “classic”
     */
    public ?string $themeFontName;

    /**
     * @var string Which invoice template is in use.
     *
     * “simple, “modern”, or “classic”
     */
    public ?string $themeLayout;

    /**
     * @var string Primary highlight colour for the invoice.
     *
     * eg. “#345beb”
     */
    public ?string $themePrimaryColor;

    public function __construct(array $data = [])
    {
        $this->invoiceId = $data['invoiceid'] ?? null;
        $this->dateFormat = $data['date_format'] ?? null;
        $this->imageBannerSrc = $data['image_banner_src'] ?? null;
        $this->imageLogoSrc = $data['image_logo_src'] ?? null;
        $this->themeFontName = $data['theme_font_name'] ?? null;
        $this->themeLayout = $data['theme_layout'] ?? null;
        $this->themePrimaryColor = $data['theme_primary_color'] ?? null;
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        return [
            'invoiceid' => $this->invoiceId,
            'date_format' => $this->dateFormat,
            'image_banner_src' => $this->imageBannerSrc,
            'image_logo_src' => $this->imageLogoSrc,
            'theme_font_name' => $this->themeFontName,
            'theme_layout' => $this->themeLayout,
            'theme_primary_color' => $this->themePrimaryColor
        ];
    }
}
