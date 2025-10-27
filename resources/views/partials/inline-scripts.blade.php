<script>
// Global inline scripts
(function(){
  // Theme toggle: toggles a data-theme attribute and persists to localStorage
  window.toggleTheme = function(){
    try {
      const root = document.documentElement;
      const current = localStorage.getItem('theme') || 'system';
      // cycle: system -> dark -> light -> system
      let next = 'dark';
      if (current === 'dark') next = 'light';
      if (current === 'light') next = 'system';
      localStorage.setItem('theme', next);
      applyTheme(next);
    } catch(e) {}
  };
  function applyTheme(mode){
    const root = document.documentElement;
    root.removeAttribute('data-theme');
    if (mode === 'dark') root.setAttribute('data-theme','dark');
    if (mode === 'light') root.setAttribute('data-theme','light');
  }
  // init theme
  try { applyTheme(localStorage.getItem('theme') || 'system'); } catch(e) {}

  // Auto-hide flash messages if any
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('[data-flash]')?.forEach(function(el){
      setTimeout(function(){ el.classList.add('hidden'); }, 4000);
    });
  });

  // Mobile menu helper if element exists
  window.toggleMobileMenu = function(){
    const el = document.getElementById('mobileMenu');
    if (el) el.classList.toggle('hidden');
  };
})();
</script>
