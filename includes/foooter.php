<footer class="site-footer">
    <div class="footer-grid">
        <div>
            <h5><i class="fas fa-store"></i> One Click</h5>
            <p>Trusted online shopping for quality products at fair prices.</p>
        </div>
        <div>
            <h6>Contact</h6>
            <p><i class="fas fa-map-marker-alt"></i> 6/23 Balol, MEH-GUJ, INDIA</p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:2301031000061@silveroakuni.ac.in">2301031000061@silveroakuni.ac.in</a></p>
        </div>
        <div>
            <h6>Social</h6>
            <p><i class="fab fa-instagram"></i> <a href="https://instagram.com/krrishpatel_02" target="_blank">@krrishpatel_02</a></p>
        </div>
    </div>

    <p class="copyright">&copy; <?php echo date('Y'); ?> One Click. All rights reserved.</p>
</footer>

<button id="scrollTop" onclick="scrollToTop()" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>

<style>
    .site-footer {
        margin-top: 56px;
        background: #0f172a;
        color: #cbd5e1;
        padding: 32px 20px 20px;
    }

    .footer-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 24px;
    }

    .site-footer h5,
    .site-footer h6 {
        color: #f8fafc;
        margin-bottom: 10px;
    }

    .site-footer p {
        margin: 6px 0;
        font-size: 0.95rem;
        color: #cbd5e1;
    }

    .site-footer a {
        color: #67e8f9;
        text-decoration: none;
    }

    .site-footer a:hover {
        text-decoration: underline;
    }

    .copyright {
        text-align: center;
        margin-top: 18px;
        border-top: 1px solid #1e293b;
        padding-top: 14px;
        color: #94a3b8;
        font-size: 0.88rem;
    }

    #scrollTop {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 44px;
        height: 44px;
        border: 0;
        border-radius: 50%;
        background: #0f766e;
        color: #fff;
        cursor: pointer;
        display: none;
        box-shadow: 0 8px 24px rgba(15, 118, 110, 0.35);
        z-index: 999;
    }

    #scrollTop:hover {
        background: #115e59;
    }
</style>

<script>
window.onscroll = function() {
    document.getElementById("scrollTop").style.display = window.scrollY > 260 ? "inline-flex" : "none";
};

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
}
</script>

</body>
</html>