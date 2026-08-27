export const apiClient = async (
  endpoint: string,
  options: RequestInit = {}
) => {
  const baseURL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';
  const url = `${baseURL}${endpoint.startsWith('/') ? endpoint : `/${endpoint}`}`;

  const headers = new Headers(options.headers);
  headers.set('Content-Type', 'application/json');
  headers.set('Accept', 'application/json');
  
  // TODO: Add Authorization header here once auth is implemented
  // const token = localStorage.getItem('token');
  // if (token) {
  //   headers.set('Authorization', `Bearer ${token}`);
  // }

  const config: RequestInit = {
    ...options,
    headers,
  };

  const response = await fetch(url, config);
  
  if (!response.ok) {
    throw new Error(`API call failed: ${response.statusText}`);
  }

  return response.json();
};
