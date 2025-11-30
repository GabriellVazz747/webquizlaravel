export async function apiFetch(url, options = {}) {
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`,
    },
    ...options
  }
  
  const res = await fetch(url, defaultOptions)
  if (!res.ok) {
    throw new Error(`API Error: ${res.statusText}`)
  }
  return res.json()
}