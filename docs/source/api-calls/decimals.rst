Decimal Values
==============

This SDK makes use of the `spryker/decimal-object <https://packagist.org/packages/spryker/decimal-object>`_ package.
All monetary amounts are represented as as ``Spryker\DecimalObject\Decimal``, so it is recommended that you refer to
`their documentation <https://github.com/spryker/decimal-object/tree/master/docs>`_.

.. code-block:: php
   use Spryker\DecimalObject\Decimal;

   $this->assertEquals(Decimal::create('41.94'), $invoice->amount->amount);
