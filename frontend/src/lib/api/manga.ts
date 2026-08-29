import { apiClient } from './client';
import type { MangaSummary } from '../types/api';

export const getRecommendations = async (
  format: 'manga' | 'manhwa' | 'manhua',
  limit: number = 10
): Promise<MangaSummary[]> => {
  const response = await apiClient(`/manga/recommendations?format=${format}&limit=${limit}`);
  return response.data;
};
