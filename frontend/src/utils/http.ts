export async function fetchWithAuth(url: string, options: RequestInit = {}) {
  const response = await fetch(url, options);

  if (response.status === 401) {
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = '/login';
    throw new Error('Unauthorized');
  }

  return response;
}
