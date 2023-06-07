# FreshBooks PHP SDK Changelog

## Unreleased

- Remove warnings in PHP 8.2
- Handle new API version accounting errors

## 0.6.0

- Added Projects resource
- Added Expense Categories resource
- Added list sort builder
- Updated list models with new `pages()` function to standardize pagination with different models
- Make invoice LineItem's taxNumber1 and taxNumber2 readonly due to FreshBooks API change

## 0.5.0

- Fixed issue with sending invoice models fields that are only write-on-create
- Support expense attachments
- Added support for invoice presentations
- Added fields to Business model (address and phone)

## 0.4.0

- Support for items
- Support for tasks
- Change quantity field on invoice lines and items to float

## 0.3.0

- Support for expenses
- Support for resource includes
- Support for resource list filters
- Support for PHP 8.1

## 0.2.0

- Add OAuth authentication methods
- Add current user and identity model (identity, business, business membership)
- Set dependencies to stable versions

## 0.1.0

Basic Invoicing workflow functionality

- Supports clients, invoices, line items, payments, taxes
