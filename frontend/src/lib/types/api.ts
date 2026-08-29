export interface MangaSummary {
  id: string;
  title: string;
  cover_url: string;
  format: 'manhwa' | 'manga' | 'manhua' | 'other';
  status: 'ongoing' | 'completed' | 'hiatus' | 'cancelled';
  rating: number;
  views_label: string;
  latest_chapter: {
    id: string;
    number: string;
    readable_at: string;
  } | null;
  is_new: boolean;
  tags: string[];
}
