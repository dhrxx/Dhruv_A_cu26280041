// Question 8: Secure the API with token-based authentication, and consume it from a JavaScript front end using fetch().
// Provide the short-lived token in the page through a server-rendered, escaped data attribute.
async function loadProducts(apiToken) {
  const response = await fetch('/api/products', {
    headers: { Authorization: `Bearer ${apiToken}`, Accept: 'application/json' },
    credentials: 'same-origin'
  });
  if (!response.ok) {
    throw new Error(`Product API returned HTTP ${response.status}`);
  }
  const products = await response.json();
  const list = document.querySelector('#product-list');
  if (!list) return;
  list.replaceChildren(...products.map((product) => {
    const item = document.createElement('li');
    item.textContent = `${product.name} - ${product.price}`;
    return item;
  }));
}

const apiForm = document.querySelector('#api-auth-form');
apiForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const form = event.currentTarget;
  const status = document.querySelector('#api-status');
  const data = new FormData(form);
  try {
    const tokenResponse = await fetch('/api/token', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        email: data.get('email'),
        password: data.get('password')
      })
    });
    const result = await tokenResponse.json();
    if (!tokenResponse.ok) throw new Error(result.error || `HTTP ${tokenResponse.status}`);
    form.elements.password.value = '';
    await loadProducts(result.token);
    if (status) status.textContent = 'Products loaded using a short-lived API token.';
  } catch (error) {
    if (status) status.textContent = error instanceof Error ? error.message : 'API request failed.';
  }
});
