import { Button, Tag, Card } from '@/components/ui'
import { ComicCard } from '@/components/comic'

export default function ComponentsPreview() {
  const dummyComics = [
    {
      id: '1',
      title: 'Solo Leveling',
      cover_url: 'https://picsum.photos/400/600?random=1',
      format: 'manhwa',
      status: 'completed',
      rating: 9.8,
      views_label: '4.2M views',
      latest_chapter: { id: 'c1', number: '200', readable_at: new Date(Date.now() - 2 * 3600 * 1000).toISOString() }, // 2 hours ago
      is_new: true,
      tags: ['action', 'fantasy']
    },
    {
      id: '2',
      title: 'That Time I Got Reincarnated as a Slime',
      cover_url: 'https://picsum.photos/400/600?random=2',
      format: 'manga',
      status: 'ongoing',
      rating: 8.9,
      views_label: '1.1M views',
      latest_chapter: { id: 'c2', number: '112', readable_at: new Date(Date.now() - 3 * 24 * 3600 * 1000).toISOString() }, // 3 days ago
      is_new: false,
      tags: ['isekai', 'fantasy']
    },
    {
      id: '3',
      title: 'A Very Long Title That Should Break Into Two Lines And Truncate Gracefully In The Card',
      cover_url: 'https://picsum.photos/400/600?random=3',
      format: 'manhua',
      status: 'ongoing',
      rating: 7.5,
      views_label: '450K views',
      latest_chapter: { id: 'c3', number: '55', readable_at: new Date(Date.now() - 5 * 24 * 3600 * 1000).toISOString() }, // 5 days ago
      is_new: false,
      tags: ['martial arts']
    },
    {
      id: '4',
      title: 'No Chapter Manga (Test Null Chapter)',
      cover_url: 'https://picsum.photos/400/600?random=4',
      format: 'manga',
      status: 'completed',
      rating: 8.0,
      views_label: '10K views',
      latest_chapter: null,
      is_new: false,
      tags: ['slice of life']
    }
  ];

  return (
    <div className="min-h-screen bg-background p-8 text-on-background">
      <h1 className="text-4xl font-bold mb-8">Scrollify UI Components</h1>
      
      <section className="mb-12">
        <h2 className="text-2xl font-bold mb-4">Buttons</h2>
        <div className="flex gap-4 items-center">
          <Button variant="primary">Primary Button</Button>
          <Button variant="secondary">Secondary Button</Button>
          <Button variant="primary" disabled>Disabled</Button>
        </div>
      </section>
      
      <section className="mb-12">
        <h2 className="text-2xl font-bold mb-4">Tags</h2>
        <div className="flex gap-4 items-center">
          <Tag variant="new">New</Tag>
          <Tag variant="genre">Action</Tag>
          <Tag variant="rating">★ 4.8</Tag>
        </div>
      </section>
      
      <section className="mb-12">
        <h2 className="text-2xl font-bold mb-4">Cards</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
          <Card className="p-6">
            <h3 className="text-xl font-bold mb-2">Standard Card</h3>
            <p className="text-on-surface-variant">This card uses the standard thick border and heavy shadow. Best for hero or featured content.</p>
          </Card>
          
          <Card compact className="p-6">
            <h3 className="text-xl font-bold mb-2">Compact Card</h3>
            <p className="text-on-surface-variant">This card uses a standard border and lighter shadow. Best for dense grid layouts.</p>
          </Card>
        </div>

        <h3 className="text-xl font-bold mb-4 mt-8">Comic Cards</h3>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {dummyComics.map(comic => (
            <ComicCard key={comic.id} {...comic} onClick={() => console.log('Clicked', comic.title)} />
          ))}
        </div>
      </section>
    </div>
  )
}
