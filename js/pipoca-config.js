/**
 * Poc Poc Gourmet — toppings e bases
 */
window.PIPOCA_BASE_CATALOG = [
  { name: 'Cremosa', desc: 'Pipoca com creme artesanal', tone: '#e91e63' },
];

window.PIPOCA_FLAVOR_CATALOG = [
  { name: 'Leite em pó', desc: 'Finalização com leite em pó', tone: '#f5e6c8', image: '' },
  { name: 'Ovomaltine em pó', desc: 'Finalização com Ovomaltine', tone: '#5c3420', image: '' },
  { name: 'Nenhum', desc: 'Sem topping extra', tone: '#e8c9d4', image: '' },
];

window.PIPOCA_FLAVOR_NAMES = window.PIPOCA_FLAVOR_CATALOG.map((item) => item.name);
