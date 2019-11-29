<?php
  use Webkul\Payment\Payment\Payment;
  use Billplz\Client;
  $billplz = app('Artanis\BillPlz\Payment\BillPlz');

  $billplzCreate = Client::make('155994cc-37ea-4c78-9460-1062df930f2c', 'S-b4db8m12r7Te8JmS9O79Rg')->useSandbox();
  $billplzCreate->create(
      "x7afhxzc",
      "hakim@gmail.com",
      'LUQMAN',
      \Duit\MYR::given(200),
      " Item : ",
      "EMAS",
      ['callback_url' => route('billplz.cancel'), 'redirect_url' => route('billplz.redirect')],
      "Testing API"
  );

?>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the FPX website in a few seconds.


    <form action="{{ $billplz->getBillPlzlUrl() }}" id="billplz_checkout" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">

        @foreach ($billplz->getFormFields() as $name => $value)

            <input type="hidden" name="{{ $name }}" value="{{ $value }}">

        @endforeach
    </form>

    <script type="text/javascript">
        document.getElementById("billplz_checkout").submit();
    </script>
</body>
