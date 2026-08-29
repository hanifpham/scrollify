

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
            className={`whitespace-nowrap px-6 py-2 rounded-full border-2 font-bold text-sm uppercase transition-colors duration-200 ${
              isActive
                ? "bg-primary border-black text-white"
                : "bg-white border-black text-black hover:bg-gray-100"
            }`}
          >
            {option.label}
          </button>
        );
      })}
    </div>
  );
}

