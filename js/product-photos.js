window.PIPOCANDO_PHOTO_MAP = {
  byId: {
    'p-ninho-500': 'products/ninho-cremoso.jpg',
    'p-ninho-1l': 'products/ninho-cremoso.jpg',
    'p-ovo-500': 'products/ovomaltine-cremoso.jpg',
    'p-ovo-1l': 'products/ovomaltine-cremoso.jpg',
    'p-ninho-ovo-500': 'products/ninho-ovomaltine.jpg',
    'p-ninho-ovo-1l': 'products/ninho-ovomaltine.jpg',
    'p-nutella-500': 'products/nutella-cremosa.jpg',
    'p-nutella-1l': 'products/nutella-cremosa.jpg',
    'p-ninho-nutella-500': 'products/ninho-nutella.jpg',
    'p-ninho-nutella-1l': 'products/ninho-nutella.jpg',
    'p-seq-ninho': 'products/leite-ninho.jpg',
    'p-seq-maracuja': 'products/torta-maracuja.jpg',
    'p-seq-limao': 'products/torta-limao.jpg',
    'p-seq-pacoquinha': 'products/pacoquinha.jpg',
    'p-caramelizada-500': 'products/caramelizada.jpg',
    'p-caramelizada-1l': 'products/caramelizada.jpg',
    'p-sequinhas-1kg': 'products/sequinhas-1kg.jpg',
    'p-embalagem': 'products/embalagem-presente.jpg',
  },
  byName: {
    'ninho cremoso 500ml': 'products/ninho-cremoso.jpg',
    'ninho cremoso 1l': 'products/ninho-cremoso.jpg',
    'ovomaltine cremoso 500ml': 'products/ovomaltine-cremoso.jpg',
    'ovomaltine cremoso 1l': 'products/ovomaltine-cremoso.jpg',
    'ninho + ovomaltine cremoso 500ml': 'products/ninho-ovomaltine.jpg',
    'ninho + ovomaltine cremoso 1l': 'products/ninho-ovomaltine.jpg',
    'nutella cremosa 500ml': 'products/nutella-cremosa.jpg',
    'nutella cremosa 1l': 'products/nutella-cremosa.jpg',
    'ninho + nutella cremoso 500ml': 'products/ninho-nutella.jpg',
    'ninho + nutella cremoso 1l': 'products/ninho-nutella.jpg',
    'leite ninho': 'products/leite-ninho.jpg',
    'torta de maracujá': 'products/torta-maracuja.jpg',
    'torta de limão': 'products/torta-limao.jpg',
    'paçoquinha': 'products/pacoquinha.jpg',
    'caramelizada 500ml': 'products/caramelizada.jpg',
    'caramelizada 1l': 'products/caramelizada.jpg',
    'sequinhas 1 kg': 'products/sequinhas-1kg.jpg',
    'embalagem para presente': 'products/embalagem-presente.jpg',
  },
};

(function () {
  const map = window.PIPOCANDO_PHOTO_MAP || { byId: {}, byName: {} };

  function lookupKnownPhoto(id, name) {
    if (id && map.byId && map.byId[id]) return map.byId[id];
    const key = String(name || '').trim().toLowerCase();
    if (key && map.byName && map.byName[key]) return map.byName[key];
    return '';
  }

  function resolveItemImage(item, products) {
    const direct = String(item?.image || '').trim();
    if (direct && !direct.startsWith('data:')) return direct;

    const known = lookupKnownPhoto(item?.productId, item?.name);
    if (known) return known;

    const list = Array.isArray(products) ? products : [];
    const product = list.find((p) => String(p.id) === String(item?.productId || ''))
      || list.find((p) => String(p.name || '').trim().toLowerCase() === String(item?.name || '').trim().toLowerCase());
    const fromProduct = String(product?.image || '').trim();
    if (fromProduct && !fromProduct.startsWith('data:')) return fromProduct;

    return known || direct;
  }

  window.AuroraPhotos = { lookupKnownPhoto, resolveItemImage };
})();
