        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Move all modals to body to ensure they are on the top stacking context
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        document.body.appendChild(modal);
    });

    // Sidebar Toggler (Desktop & Mobile)
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('sidebarContainer');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (window.innerWidth >= 992) {
                // Desktop Toggle (Hide/Show)
                document.body.classList.toggle('sidebar-hidden');
                localStorage.setItem('sidebar-hidden-pref', document.body.classList.contains('sidebar-hidden') ? 'true' : 'false');
            } else {
                // Mobile Drawer Toggle
                sidebar.classList.toggle('show');
            }
        });
        
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }

    // Restore desktop sidebar preference on load
    if (window.innerWidth >= 992) {
        const isHidden = localStorage.getItem('sidebar-hidden-pref') === 'true';
        if (isHidden) {
            document.body.classList.add('sidebar-hidden');
        }
    }

    // Maintain Sidebar Scroll Position
    if (sidebar) {
        const savedScroll = localStorage.getItem('sidebar-scroll');
        if (savedScroll !== null) {
            sidebar.scrollTop = parseInt(savedScroll, 10);
        }

        sidebar.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function() {
                localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
            });
        });

        window.addEventListener('beforeunload', function() {
            localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
        });
    }

    // Live Clock Update
    function updateClock() {
        const clockEl = document.getElementById('realtime-clock');
        if (clockEl) {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            clockEl.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
});
</script>

</body>
</html>
