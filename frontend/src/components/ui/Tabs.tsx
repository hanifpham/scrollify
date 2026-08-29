

export interface TabOption {
  value: string;
  label: string;
}

export interface TabsProps {
  options: TabOption[];
  value: string;
  onChange: (value: string) => void;
  className?: string;
}

export function Tabs({ options, value, onChange, className = '' }: TabsProps) {
  return (
    <div className={`flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide ${className}`}>
      {options.map((option) => {
        const isActive = value === option.value;
        return (
          <button
            key={option.value}
            onClick={() => onChange(option.value)}
            className={`whitespace-nowrap px-8 py-3 border-4 border-black shadow-[4px_4px_0px_0px_#000000] font-black text-lg uppercase transition-colors duration-200 rounded-none ${
              isActive
                ? "bg-primary text-on-primary"
                : "bg-pure-white text-on-surface hover:bg-zinc-200"
            }`}
          >
            {option.label}
          </button>
        );
      })}
    </div>
  );
}

