<script>window.baseUrl = '<?= url('/') ?>'; window.searchRequestUrl = '<?= url('/search') ?>'; window.checkoutUrl = '<?= url('/checkout') ?>'; window.basketRequestUrl = '<?= url('/basket/get') ?>'; window.basketAddRequestUrl = '<?= url('/basket/add') ?>'; window.basketRemoveRequestUrl = '<?= url('/basket/remove') ?>';</script>
<script src="<?= url('/assets/js/app.js') ?>"></script>
<?= isDebug() ? '<script id="__bs_script__">document.write("<script async src=\'http://HOST:3000/browser-sync/browser-sync-client.js?v=2.26.3\'><\/script>".replace("HOST", location.hostname));</script>' : null; ?>