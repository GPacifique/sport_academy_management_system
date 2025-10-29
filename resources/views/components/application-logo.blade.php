<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Sports Academy Logo: Trophy with Star -->
    <defs>
        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
        </linearGradient>
    </defs>
    
    <!-- Trophy Base -->
    <rect x="70" y="150" width="60" height="10" rx="2" fill="url(#logoGradient)"/>
    <rect x="80" y="140" width="40" height="15" rx="2" fill="url(#logoGradient)"/>
    
    <!-- Trophy Cup -->
    <path d="M 60 70 L 50 130 L 150 130 L 140 70 Z" fill="url(#logoGradient)" opacity="0.9"/>
    <ellipse cx="100" cy="70" rx="40" ry="8" fill="url(#logoGradient)"/>
    
    <!-- Trophy Handles -->
    <path d="M 50 75 Q 30 85, 30 105 T 50 115" stroke="url(#logoGradient)" stroke-width="6" fill="none" opacity="0.7"/>
    <path d="M 150 75 Q 170 85, 170 105 T 150 115" stroke="url(#logoGradient)" stroke-width="6" fill="none" opacity="0.7"/>
    
    <!-- Star on Cup -->
    <path d="M 100 85 L 105 100 L 120 100 L 108 110 L 113 125 L 100 115 L 87 125 L 92 110 L 80 100 L 95 100 Z" fill="#fbbf24" opacity="0.95"/>
</svg>
