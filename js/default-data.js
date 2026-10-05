const PIPOCANDO_DEFAULT_DATA = {

  version: 2,

  settings: {

    name: 'Poc Poc Gourmet',

    tagline: 'Pipocas Gourmet',

    brandName: 'Poc Poc',

    brandAccent: '',

    brandSub: 'Gourmet',

    slogan: 'A pipoca gourmet de Divinópolis!',

    heroLine1: 'Feito com carinho para',

    heroLine2Prefix: 'deixar seu dia mais',

    heroWords: ['doce', 'especial', 'feliz'],

    heroCategories: 'Cremes · Sequinhas · Caramelizada',

    placeShort: 'Divinópolis, MG',

    siteUrl: 'https://pocpocgourmet.seuproximosite.com.br/',
    adminUrl: 'https://pocpocgourmet.seuproximosite.com.br/admin/login.html',

    whatsappOrderMsg: 'Olá! Quero pedir pipocas gourmet 🍿',

    whatsappFloatMsg: 'Olá! Tenho uma dúvida 🍿',

    pixKey: '37988523738',
    pixName: 'Jessica Elias Coelho Miranda',
    pixBank: '',

    logo: '',

    banner: 'products/banner-hero.jpg',

    whatsapp: '5537991296906',

    instagram: 'https://www.instagram.com/poc.pocgourmet',

    instagramUser: '@poc.pocgourmet',

    facebook: '',

    email: 'jeelias18@gmail.com',

    address: 'Rua Barbosa Lagares, 648, Interlagos — Divinópolis, MG',

    hours: 'Ter–Sex 13h–19h · Sáb–Dom 13h–17h · Seg fechado',

    storeStatus: 'auto',

    openTime: '13:00',

    closeTime: '19:00',

    openDays: [0, 2, 3, 4, 5, 6],

    storeSchedule: [
      { days: [2, 4, 5], open: '13:00', close: '19:00' },
      { days: [3], open: '15:00', close: '19:00' },
      { days: [0, 6], open: '13:00', close: '17:00' },
    ],

    deliveryFee: 0,

    deliveryNote: 'Retirada no local · Entrega por Uber/99 (solicitada pelo cliente)',

    deliveryRadiusKm: 15,

    storeLat: -20.1586508,

    storeLng: -44.8705907,

    ifoodUrl: '',

    salesGoals: {
      dailyPots: 20,
      dailyRevenue: 500,
      monthlyPots: 400,
      monthlyRevenue: 10000,
    },

  },

  auth: {

    email: 'admin@pocpocgourmet.com.br',

    password: 'pocpoc123',

  },

  categories: [

    { id: 'cat-creme', name: 'Pipocas com Creme', slug: 'pipocas-com-creme' },

    { id: 'cat-sequinhas', name: 'Pipocas Sequinhas', slug: 'pipocas-sequinhas' },

    { id: 'cat-caramelizada', name: 'Pipoca Caramelizada', slug: 'pipoca-caramelizada' },

    { id: 'cat-kilo', name: 'Pipocas no Kilo', slug: 'pipocas-no-kilo' },

    { id: 'cat-presente', name: 'Embalagem de Presente', slug: 'embalagem-de-presente' },

  ],

  products: [
    {
      id: 'p-ninho-500',
      name: 'Ninho Cremoso 500ml',
      description: 'Pipoca com creme de Ninho. Pote 500ml. Inclui escolha de topping: leite em pó, Ovomaltine ou nenhum.',
      price: 24,
      categoryId: 'cat-creme',
      image: 'products/ninho-cremoso.jpg',
      featured: true,
      slug: 'ninho-cremoso-500',
      size: '500ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      bestSeller: true,
      active: true,
      available: true,
      sortOrder: 1,
    },
    {
      id: 'p-ninho-1l',
      name: 'Ninho Cremoso 1L',
      description: 'Pipoca com creme de Ninho. Pote 1L. Inclui escolha de topping: leite em pó, Ovomaltine ou nenhum.',
      price: 40,
      categoryId: 'cat-creme',
      image: 'products/ninho-cremoso.jpg',
      featured: true,
      slug: 'ninho-cremoso-1l',
      size: '1000ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      bestSeller: true,
      active: true,
      available: true,
      sortOrder: 2,
    },
    {
      id: 'p-ovo-500',
      name: 'Ovomaltine Cremoso 500ml',
      description: 'Pipoca com creme de Ovomaltine. Pote 500ml. Inclui escolha de topping: leite em pó, Ovomaltine ou nenhum.',
      price: 24,
      categoryId: 'cat-creme',
      image: 'products/ovomaltine-cremoso.jpg',
      featured: true,
      slug: 'ovomaltine-cremoso-500',
      size: '500ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      bestSeller: true,
      active: true,
      available: true,
      sortOrder: 3,
    },
    {
      id: 'p-ovo-1l',
      name: 'Ovomaltine Cremoso 1L',
      description: 'Pipoca com creme de Ovomaltine. Pote 1L. Inclui escolha de topping: leite em pó, Ovomaltine ou nenhum.',
      price: 40,
      categoryId: 'cat-creme',
      image: 'products/ovomaltine-cremoso.jpg',
      featured: true,
      slug: 'ovomaltine-cremoso-1l',
      size: '1000ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      active: true,
      available: true,
      sortOrder: 4,
    },
    {
      id: 'p-ninho-ovo-500',
      name: 'Ninho + Ovomaltine Cremoso 500ml',
      description: 'Pipoca meio a meio Ninho e Ovomaltine. Pote 500ml. Inclui topping: leite em pó, Ovomaltine ou nenhum.',
      price: 24,
      categoryId: 'cat-creme',
      image: 'products/ninho-ovomaltine.jpg',
      featured: true,
      slug: 'ninho-ovomaltine-500',
      size: '500ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      active: true,
      available: true,
      sortOrder: 5,
    },
    {
      id: 'p-ninho-ovo-1l',
      name: 'Ninho + Ovomaltine Cremoso 1L',
      description: 'Pipoca meio a meio Ninho e Ovomaltine. Pote 1L. Inclui topping: leite em pó, Ovomaltine ou nenhum.',
      price: 40,
      categoryId: 'cat-creme',
      image: 'products/ninho-ovomaltine.jpg',
      featured: true,
      slug: 'ninho-ovomaltine-1l',
      size: '1000ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      active: true,
      available: true,
      sortOrder: 6,
    },
    {
      id: 'p-nutella-500',
      name: 'Nutella Cremosa 500ml',
      description: 'Pipoca com creme de Nutella. Pote 500ml. Inclui escolha de topping: leite em pó, Ovomaltine ou nenhum.',
      price: 28,
      categoryId: 'cat-creme',
      image: 'products/nutella-cremosa.jpg',
      featured: true,
      slug: 'nutella-cremosa-500',
      size: '500ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      bestSeller: true,
      active: true,
      available: true,
      sortOrder: 7,
    },
    {
      id: 'p-nutella-1l',
      name: 'Nutella Cremosa 1L',
      description: 'Pipoca com creme de Nutella. Pote 1L. Inclui escolha de topping: leite em pó, Ovomaltine ou nenhum.',
      price: 48,
      categoryId: 'cat-creme',
      image: 'products/nutella-cremosa.jpg',
      featured: true,
      slug: 'nutella-cremosa-1l',
      size: '1000ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      active: true,
      available: true,
      sortOrder: 8,
    },
    {
      id: 'p-ninho-nutella-500',
      name: 'Ninho + Nutella Cremoso 500ml',
      description: 'Pipoca meio a meio Ninho e Nutella. Pote 500ml. Inclui topping: leite em pó, Ovomaltine ou nenhum.',
      price: 28,
      categoryId: 'cat-creme',
      image: 'products/ninho-nutella.jpg',
      featured: true,
      slug: 'ninho-nutella-500',
      size: '500ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      active: true,
      available: true,
      sortOrder: 9,
    },
    {
      id: 'p-ninho-nutella-1l',
      name: 'Ninho + Nutella Cremoso 1L',
      description: 'Pipoca meio a meio Ninho e Nutella. Pote 1L. Inclui topping: leite em pó, Ovomaltine ou nenhum.',
      price: 48,
      categoryId: 'cat-creme',
      image: 'products/ninho-nutella.jpg',
      featured: true,
      slug: 'ninho-nutella-1l',
      size: '1000ml',
      flavorSlots: 1,
      flavors: ['Leite em pó', 'Ovomaltine em pó', 'Nenhum'],
      active: true,
      available: true,
      sortOrder: 10,
    },

    {
      id: 'p-seq-ninho',
      name: 'Leite Ninho',
      description: 'Pipoca sequinha sabor Leite Ninho. 150g em embalagem prática para conservar a crocância.',
      price: 20,
      categoryId: 'cat-sequinhas',
      image: 'products/leite-ninho.jpg',
      featured: true,
      slug: 'leite-ninho',
      size: '150g',
      flavorSlots: 0,
      flavors: [],
      active: true,
      available: false,
      sortOrder: 11,
    },
    {
      id: 'p-seq-maracuja',
      name: 'Torta de Maracujá',
      description: 'Pipoca sequinha sabor Torta de Maracujá. 150g em embalagem prática para conservar a crocância.',
      price: 20,
      categoryId: 'cat-sequinhas',
      image: 'products/torta-maracuja.jpg',
      featured: true,
      slug: 'torta-de-maracuja',
      size: '150g',
      flavorSlots: 0,
      flavors: [],
      bestSeller: true,
      active: true,
      available: true,
      sortOrder: 12,
    },
    {
      id: 'p-seq-limao',
      name: 'Torta de Limão',
      description: 'Pipoca sequinha sabor Torta de Limão. 150g em embalagem prática para conservar a crocância.',
      price: 20,
      categoryId: 'cat-sequinhas',
      image: 'products/torta-limao.jpg',
      featured: true,
      slug: 'torta-de-limao',
      size: '150g',
      flavorSlots: 0,
      flavors: [],
      active: true,
      available: true,
      sortOrder: 13,
    },
    {
      id: 'p-seq-pacoquinha',
      name: 'Paçoquinha',
      description: 'Pipoca sequinha sabor Paçoquinha. 150g em embalagem prática para conservar a crocância.',
      price: 20,
      categoryId: 'cat-sequinhas',
      image: 'products/pacoquinha.jpg',
      featured: true,
      slug: 'pacoquinha',
      size: '150g',
      flavorSlots: 0,
      flavors: [],
      active: true,
      available: false,
      sortOrder: 14,
    },

    {
      id: 'p-caramelizada-500',
      name: 'Caramelizada 500ml',
      description: 'Pipoca caramelizada clássica, crocante e dourada. Pote 500ml. Sem creme.',
      price: 18,
      categoryId: 'cat-caramelizada',
      image: 'products/caramelizada.jpg',
      featured: true,
      slug: 'caramelizada-500',
      size: '500ml',
      flavorSlots: 0,
      flavors: [],
      bestSeller: true,
      active: true,
      available: true,
      sortOrder: 15,
    },
    {
      id: 'p-caramelizada-1l',
      name: 'Caramelizada 1L',
      description: 'Pipoca caramelizada clássica, crocante e dourada. Pote 1L. Sem creme.',
      price: 35,
      categoryId: 'cat-caramelizada',
      image: 'products/caramelizada.jpg',
      featured: true,
      slug: 'caramelizada-1l',
      size: '1000ml',
      flavorSlots: 0,
      flavors: [],
      active: true,
      available: true,
      sortOrder: 16,
    },

    {
      id: 'p-sequinhas-1kg',
      name: 'Sequinhas 1 kg',
      description: '1 kg de pipocas sequinhas sob encomenda (prazo 7 dias). Escolha o sabor: Ninho, Morango, Ovomaltine, Maracujá, Paçoquinha ou Torta de Limão.',
      price: 120,
      categoryId: 'cat-kilo',
      image: 'products/sequinhas-1kg.jpg',
      featured: true,
      slug: 'sequinhas-1kg',
      size: '1kg',
      flavorSlots: 1,
      flavors: ['Leite Ninho', 'Morango', 'Ovomaltine', 'Maracujá', 'Paçoquinha', 'Torta de Limão'],
      active: true,
      available: true,
      sortOrder: 17,
    },

    {
      id: 'p-embalagem',
      name: 'Embalagem para Presente',
      description: 'Embalagem para presente com cartinha personalizada para mensagens especiais.',
      price: 6,
      categoryId: 'cat-presente',
      image: 'products/embalagem-presente.jpg',
      featured: true,
      slug: 'embalagem-presente',
      size: 'Único',
      flavorSlots: 0,
      flavors: [],
      active: true,
      available: true,
      sortOrder: 18,
    },
  ],

  clients: [],

  orders: [],

  finance: [],

  coupons: [],

  reviews: [],

  faq: [],

  gallery: [
    'products/ninho-cremoso.jpg',
    'products/ovomaltine-cremoso.jpg',
    'products/ninho-ovomaltine.jpg',
    'products/nutella-cremosa.jpg',
    'products/ninho-nutella.jpg',
    'products/torta-maracuja.jpg',
    'products/torta-limao.jpg',
    'products/caramelizada.jpg',
    'products/sequinhas-1kg.jpg',
    'products/embalagem-presente.jpg',
  ],

};
