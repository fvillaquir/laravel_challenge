@component('mail::message')
# Nueva Orden Creada

Tu orden con ID #{{ $orderId }} ha sido creada exitosamente.

**Total:** ${{ $orderTotal }}

**Estado:** {{ $orderStatus }}

Gracias por tu compra.

@endcomponent