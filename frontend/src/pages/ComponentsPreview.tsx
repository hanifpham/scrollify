import { Button, Tag, Card } from '@/components/ui'

export default function ComponentsPreview() {
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
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          <Card className="p-6">
            <h3 className="text-xl font-bold mb-2">Standard Card</h3>
            <p className="text-on-surface-variant">This card uses the standard thick border and heavy shadow. Best for hero or featured content.</p>
          </Card>
          
          <Card compact className="p-6">
            <h3 className="text-xl font-bold mb-2">Compact Card</h3>
            <p className="text-on-surface-variant">This card uses a standard border and lighter shadow. Best for dense grid layouts.</p>
          </Card>
        </div>
      </section>
    </div>
  )
}
