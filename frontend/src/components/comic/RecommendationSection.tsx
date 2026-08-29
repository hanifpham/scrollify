import { useState, useEffect } from 'react';
import { SectionHeader } from '../layout';
import { ComicCard } from './ComicCard';
import { Tabs, Button, Card } from '../ui';
import { getRecommendations } from '@/lib/api/manga';
import type { MangaSummary } from '@/lib/types/api';

type Format = 'manhwa' | 'manga' | 'manhua';

const formatOptions = [
  { value: 'manhwa', label: 'Manhwa' },
  { value: 'manga', label: 'Manga' },
  { value: 'manhua', label: 'Manhua' },
];

export function RecommendationSection() {
  const [activeFormat, setActiveFormat] = useState<Format>('manhwa');
  const [data, setData] = useState<MangaSummary[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchRecommendations = async () => {
    setIsLoading(true);
    setError(null);
    try {
      const recommendations = await getRecommendations(activeFormat, 5); // Desktop shows 5 columns, fetch 5 items
      setData(recommendations);
    } catch (err: any) {
      console.error('Error fetching recommendations:', err);
      setError('Gagal memuat rekomendasi');
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchRecommendations();
  }, [activeFormat]);

  return (
    <section className="mb-12">
      <SectionHeader title="REKOMENDASI" variant="amber" />
      
      <div className="mb-6 mt-4">
        <Tabs
          options={formatOptions}
          value={activeFormat}
          onChange={(val) => setActiveFormat(val as Format)}
        />
      </div>

      {error ? (
        <Card className="py-10 flex flex-col items-center justify-center gap-4">
          <p className="text-lg font-bold">{error}</p>
          <Button variant="secondary" onClick={fetchRecommendations}>Coba Lagi</Button>
        </Card>
      ) : isLoading ? (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 items-stretch">
          {Array.from({ length: 5 }).map((_, i) => (
            <div key={i} className="w-full animate-pulse flex flex-col gap-2">
              <div className="w-full aspect-3/4 bg-gray-200 border-border-thick border-black rounded-none shadow-[10px_10px_0px_0px_#000000]" />
              <div className="h-6 bg-gray-200 w-3/4 rounded-none border-border-thick border-black" />
              <div className="h-4 bg-gray-200 w-1/2 rounded-none border-border-thick border-black" />
            </div>
          ))}
        </div>
      ) : data.length === 0 ? (
        <div className="py-10 text-center border-2 border-black bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
          <p className="text-lg font-bold text-gray-500">Belum ada rekomendasi untuk kategori ini</p>
        </div>
      ) : (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 items-stretch">
          {data.map((comic) => (
            <div key={comic.id} className="w-full shrink-0">
              <ComicCard {...comic} />
            </div>
          ))}
        </div>
      )}
    </section>
  );
}
