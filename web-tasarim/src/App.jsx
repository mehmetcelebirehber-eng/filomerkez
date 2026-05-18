import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Bus, Menu, X, Phone, Mail, MapPin } from 'lucide-react';
import Hero from './components/Hero';
import QuickServices from './components/QuickServices';
import Services from './components/Services';
import AracFilomuz from './components/AracFilomuz';
import QuoteForm from './components/QuoteForm';
import OgrenciTasimaciligi from './components/OgrenciTasimaciligi';
import PersonelTasimaciligi from './components/PersonelTasimaciligi';
import TurizmTasimaciligi from './components/TurizmTasimaciligi';
import VipTransfer from './components/VipTransfer';
import AracKiralama from './components/AracKiralama';
import IletisimPage from './components/IletisimPage';
import WhatsAppButton from './components/WhatsAppButton';
import FiloDetay from './components/FiloDetay';
import AboutUs from './components/AboutUs';
import FilomuzPage from './components/FilomuzPage';
import HizmetlerimizPage from './components/HizmetlerimizPage';

function App() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  // Define SVG gradient for menu
  const svgGradients = (
    <svg width="0" height="0" style={{ position: 'absolute' }}>
      <defs>
        <linearGradient id="menu-grad" x1="0%" y1="0%" x2="100%" y2="0%">
          <stop offset="0%" stopColor="#10549c" />
          <stop offset="100%" stopColor="#e21b1b" />
        </linearGradient>
        <linearGradient id="text-grad" x1="0%" y1="0%" x2="100%" y2="0%">
          <stop offset="0%" stopColor="#10549c" />
          <stop offset="100%" stopColor="#e21b1b" />
        </linearGradient>
      </defs>
    </svg>
  );
  const [activeServiceIndex, setActiveServiceIndex] = useState(0);
  const [currentPage, setCurrentPage] = useState('home');
  const [selectedFleetCategory, setSelectedFleetCategory] = useState('otobus');
  const [isIntroPlaying, setIsIntroPlaying] = useState(true);

  useEffect(() => {
    // 5 seconds intro video timer
    const introTimer = setTimeout(() => {
      setIsIntroPlaying(false);
    }, 5000);
    return () => clearTimeout(introTimer);
  }, []);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div style={{ position: 'relative' }}>
      {svgGradients}

      {/* Fullscreen Overlay Menu -> Side Panel */}
      <AnimatePresence>
        {mobileMenuOpen && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setMobileMenuOpen(false)}
              style={{
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                height: '100vh',
                background: 'rgba(0,0,0,0.6)',
                backdropFilter: 'blur(4px)',
                zIndex: 999998
              }}
            />
            <motion.div
              initial={{ opacity: 0, x: '100%' }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: '100%' }}
              transition={{ type: 'spring', damping: 25, stiffness: 200 }}
              style={{
                position: 'fixed',
                top: 0,
                right: 0,
                width: '100%',
                maxWidth: '400px',
                height: '100vh',
                background: '#ffffff',
                boxShadow: '-10px 0 40px rgba(0,0,0,0.15)',
                zIndex: 999999,
                display: 'flex',
                flexDirection: 'column',
                padding: '60px 40px',
              }}
            >
              <button 
                onClick={() => setMobileMenuOpen(false)}
                style={{ position: 'absolute', top: '30px', right: '30px', background: '#f0f4f8', border: 'none', borderRadius: '50%', width: '40px', height: '40px', cursor: 'pointer', color: '#10549c', display: 'flex', alignItems: 'center', justifyContent: 'center' }}
              >
                <X size={24} />
              </button>

              <div style={{ marginBottom: '40px' }}>
                <h3 style={{ color: 'var(--color-accent-secondary)', fontSize: '0.85rem', letterSpacing: '3px', textTransform: 'uppercase', marginBottom: '15px', fontWeight: 600 }}>Menü</h3>
                <div style={{ width: '40px', height: '3px', background: 'linear-gradient(to right, #10549c, #e21b1b)' }}></div>
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: '25px' }}>
                {[
                  { id: 'anasayfa', label: 'ANASAYFA' },
                  { id: 'hakkimizda', label: 'HAKKIMIZDA' },
                  { id: 'hizmetlerimiz', label: 'HİZMETLERİMİZ' },
                  { id: 'filomuz', label: 'FİLOMUZ' },
                  { id: 'medya', label: 'MEDYA' },
                  { id: 'iletisim', label: 'BİZE ULAŞIN' }
                ].map((item, idx) => (
                  <motion.a
                    key={item.id}
                    href={`#${item.id}`}
                    onClick={(e) => {
                      setMobileMenuOpen(false);
                      if (item.id === 'anasayfa') {
                        e.preventDefault();
                        setCurrentPage('home');
                        window.scrollTo(0, 0);
                      } else if (item.id === 'hakkimizda') {
                        e.preventDefault();
                        setCurrentPage('hakkimizda');
                        window.scrollTo(0, 0);
                      } else if (item.id === 'iletisim') {
                        e.preventDefault();
                        setCurrentPage('iletisim');
                        window.scrollTo(0, 0);
                      } else if (item.id === 'hizmetlerimiz') {
                        e.preventDefault();
                        setCurrentPage('hizmetlerimiz');
                        window.scrollTo(0, 0);
                      } else if (item.id === 'filomuz') {
                        e.preventDefault();
                        setCurrentPage('filomuz');
                        window.scrollTo(0, 0);
                      } else if (currentPage !== 'home') {
                        e.preventDefault();
                        setCurrentPage('home');
                        setTimeout(() => {
                          document.getElementById(item.id)?.scrollIntoView({ behavior: 'smooth' });
                        }, 100);
                      } else {
                        e.preventDefault();
                        document.getElementById(item.id)?.scrollIntoView({ behavior: 'smooth' });
                      }
                    }}
                    whileHover={{ x: 10, color: '#e21b1b' }}
                    initial={{ opacity: 0, x: 50 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: idx * 0.05 + 0.1, duration: 0.3 }}
                    style={{
                      fontSize: '1.4rem',
                      fontWeight: 700,
                      fontFamily: 'var(--font-heading)',
                      color: '#10549c',
                      textDecoration: 'none',
                      textTransform: 'uppercase',
                      letterSpacing: '1px',
                      transition: 'color 0.3s ease'
                    }}
                  >
                    {item.label}
                  </motion.a>
                ))}
              </div>

              <div style={{ marginTop: 'auto', paddingTop: '30px', borderTop: '1px solid rgba(0,0,0,0.1)' }}>
                <p style={{ fontSize: '0.9rem', color: '#666', lineHeight: 1.8, margin: 0 }}>
                  <strong style={{ color: '#10549c' }}>Mehmet Çelebi Turizm</strong><br/>
                  +90 532 473 35 64<br/>
                  info@mehmetcelebiturizm.com
                </p>
              </div>
            </motion.div>
          </>
        )}
      </AnimatePresence>

      {/* Navbar */}
      <motion.nav
        className="mobile-nav-container"
        style={{
          position: 'fixed',
          top: 0,
          left: 0,
          right: 0,
          zIndex: 100,
          padding: '0 5%',
          background: '#FFFFFF',
          borderBottom: '1px solid rgba(0,0,0,0.05)',
          boxShadow: '0 2px 10px rgba(0,0,0,0.05)',
          display: 'grid',
          gridTemplateColumns: 'auto 1fr auto',
          alignItems: 'center',
          height: '100px',
          overflow: 'visible'
        }}
        initial={{ y: -100 }}
        animate={{ y: 0 }}
        transition={{ duration: 0.6 }}
      >
        {/* Logo Container */}
        <div 
          className="mobile-logo-container"
          onClick={() => {
            setCurrentPage('home');
            window.scrollTo(0, 0);
          }}
          style={{ position: 'relative', height: '100px', width: '300px', display: 'flex', alignItems: 'center', overflow: 'visible', cursor: 'pointer', marginLeft: '80px' }}
        >
          <img 
            className="mobile-logo-img"
            src="/images/logo.png" 
            alt="Mehmet Çelebi Turizm" 
            style={{ 
              position: 'absolute',
              top: '-35px', /* Görselin üstündeki şeffaf boşluğu yoksaymak için yukarı çekildi */
              left: '0',
              height: '170px', /* Logo daha da büyütüldü */
              width: 'auto',
              maxWidth: '400px', 
              objectFit: 'contain',
              objectPosition: 'left top', 
              zIndex: 9999, 
              filter: 'drop-shadow(0 6px 12px rgba(0,0,0,0.2))'
            }} 
            onError={(e) => {
              e.target.style.display = 'none';
              e.target.nextSibling.style.display = 'flex';
            }}
          />
          <span style={{ display: 'none', flexDirection: 'column', lineHeight: 1, color: 'var(--color-accent)', fontWeight: 800, fontSize: '1.5rem' }}>
            <span>Mehmet Çelebi</span>
            <span style={{ fontWeight: 300, fontSize: '0.8rem', color: 'var(--color-accent-secondary)' }}>Turizm</span>
          </span>
        </div>



        {/* Center / Right Section */}
        <div className="mobile-menu-wrapper" style={{ display: 'flex', alignItems: 'center', gap: '30px', flex: 1, justifyContent: 'flex-end' }}>
          
          {/* Hamburger Menu & Search */}
          <div className="mobile-menu-box" style={{ display: 'flex', alignItems: 'center', gap: '20px', marginRight: '30px' }}>
            <div style={{ height: '30px', width: '1px', background: 'rgba(16,84,156,0.15)' }}></div>
            <motion.button 
              className="mobile-search-btn"
              whileHover={{ scale: 1.1 }}
              style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#10549c', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </motion.button>
            <div style={{ height: '30px', width: '1px', background: 'rgba(16,84,156,0.15)' }}></div>
            <motion.button 
              className="mobile-menu-btn"
              onClick={() => setMobileMenuOpen(true)}
              whileHover={{ scale: 1.05 }}
              style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', background: 'none', border: 'none', cursor: 'pointer', gap: '6px' }}>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10549c" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                <line x1="4" y1="7" x2="20" y2="7"></line>
                <line x1="4" y1="12" x2="14" y2="12"></line>
                <line x1="4" y1="17" x2="20" y2="17"></line>
              </svg>
              <span style={{ fontSize: '0.75rem', fontWeight: 300, letterSpacing: '2px', color: '#10549c', fontFamily: 'var(--font-body)' }}>MENÜ</span>
            </motion.button>
          </div>
          
          {/* Social Icons (Premium Styling) */}
          <div className="mobile-socials" style={{ display: 'flex', gap: '15px', alignItems: 'center', borderRight: '2px solid rgba(0,0,0,0.1)', paddingRight: '20px' }}>
            
            {/* Facebook */}
            <motion.a 
              href="https://www.facebook.com/mehmetcelebiturizm/?locale=tr_TR" target="_blank" rel="noopener noreferrer"
              whileHover={{ scale: 1.15, backgroundColor: '#1877F2', color: '#FFF', boxShadow: '0 8px 20px rgba(24,119,242,0.4)' }}
              style={{ width: '40px', height: '40px', borderRadius: '50%', background: '#f0f4f8', color: '#10549c', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.3s ease' }}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
            </motion.a>

            {/* Instagram */}
            <motion.a 
              href="https://www.instagram.com/mehmetcelebiturizm/" target="_blank" rel="noopener noreferrer"
              whileHover={{ scale: 1.15, backgroundImage: 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)', color: '#FFF', boxShadow: '0 8px 20px rgba(220,39,67,0.4)' }}
              style={{ width: '40px', height: '40px', borderRadius: '50%', background: '#f0f4f8', color: '#10549c', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.3s ease' }}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
            </motion.a>

            {/* YouTube */}
            <motion.a 
              href="https://www.youtube.com/@mehmetcelebiturizm" target="_blank" rel="noopener noreferrer"
              whileHover={{ scale: 1.15, backgroundColor: '#FF0000', color: '#FFF', boxShadow: '0 8px 20px rgba(255,0,0,0.4)' }}
              style={{ width: '40px', height: '40px', borderRadius: '50%', background: '#f0f4f8', color: '#10549c', display: 'flex', alignItems: 'center', justifyContent: 'center', transition: 'all 0.3s ease' }}
            >
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
            </motion.a>
          </div>

          <motion.a 
            className="mobile-login-btn"
            href="https://mehmetcelebiturizm.com/app/login" 
            whileHover={{ scale: 1.05 }}
            style={{ 
              textDecoration: 'none',
              display: 'flex',
              alignItems: 'center',
              marginRight: '20px',
            }}
          >
            <motion.span 
              animate={{ backgroundPosition: ['200% center', '0% center'] }}
              transition={{ repeat: Infinity, duration: 3, ease: "linear" }}
              style={{
                fontSize: '1.35rem',
                fontWeight: 800,
                fontFamily: 'var(--font-heading)',
                background: 'linear-gradient(to right, #10549c 20%, #e21b1b 50%, #10549c 80%)',
                backgroundSize: '200% auto',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
                display: 'inline-block',
                letterSpacing: '1px'
              }}
            >
              GİRİŞ YAP
            </motion.span>
          </motion.a>
        </div>
      </motion.nav>

      {/* Main Content Area */}
      <AnimatePresence mode="wait">
        {currentPage === 'home' ? (
          <motion.main 
            key="home"
            className="main-content-wrapper"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
          >
            <Hero 
              isIntroPlaying={isIntroPlaying}
              activeIndex={activeServiceIndex} 
              onVideoEnd={() => setActiveServiceIndex(prev => (prev + 1) % 5)} 
              onNext={() => setActiveServiceIndex(prev => (prev + 1) % 5)}
              onPrev={() => setActiveServiceIndex(prev => (prev === 0 ? 4 : prev - 1))}
              onCTAClick={(index) => {
                if (index === 0) { // Personel Taşımacılığı
                  setCurrentPage('personel');
                  window.scrollTo(0, 0);
                } else if (index === 1) { // Öğrenci Taşımacılığı
                  setCurrentPage('ogrenci');
                  window.scrollTo(0, 0);
                } else if (index === 2) { // Turizm Taşımacılığı
                  setCurrentPage('turizm');
                  window.scrollTo(0, 0);
                } else if (index === 3) { // VIP Transfer
                  setCurrentPage('vip');
                  window.scrollTo(0, 0);
                } else if (index === 4) { // Araç Kiralama
                  setCurrentPage('kiralama');
                  window.scrollTo(0, 0);
                } else {
                  // Diğer hizmetler için şimdilik form'a kaydır
                  document.getElementById('iletisim')?.scrollIntoView({ behavior: 'smooth' });
                }
              }}
            />
            <QuickServices 
              activeIndex={activeServiceIndex} 
              onServiceClick={(idx) => {
                setActiveServiceIndex(idx);
                if (idx === 0) {
                  setCurrentPage('personel');
                  window.scrollTo(0, 0);
                } else if (idx === 1) {
                  setCurrentPage('ogrenci');
                  window.scrollTo(0, 0);
                } else if (idx === 2) {
                  setCurrentPage('turizm');
                  window.scrollTo(0, 0);
                } else if (idx === 3) {
                  setCurrentPage('vip');
                  window.scrollTo(0, 0);
                } else if (idx === 4) {
                  setCurrentPage('kiralama');
                  window.scrollTo(0, 0);
                }
              }} 
            />
            <AracFilomuz 
            onVehicleClick={(id) => {
              setSelectedFleetCategory(id);
              setCurrentPage('filo-detay');
              window.scrollTo(0, 0);
            }}
          />
            <QuoteForm />
            <Services 
              onServiceClick={(idx) => {
                setActiveServiceIndex(idx);
                if (idx === 0) {
                  setCurrentPage('personel');
                  window.scrollTo(0, 0);
                } else if (idx === 1) {
                  setCurrentPage('ogrenci');
                  window.scrollTo(0, 0);
                } else if (idx === 2) {
                  setCurrentPage('turizm');
                  window.scrollTo(0, 0);
                } else if (idx === 3) {
                  setCurrentPage('vip');
                  window.scrollTo(0, 0);
                } else if (idx === 4) {
                  setCurrentPage('kiralama');
                  window.scrollTo(0, 0);
                }
              }} 
            />
          </motion.main>
        ) : currentPage === 'hakkimizda' ? (
          <motion.div key="hakkimizda" className="main-content-wrapper">
            <AboutUs />
          </motion.div>
        ) : currentPage === 'hizmetlerimiz' ? (
          <motion.div key="hizmetlerimiz" className="main-content-wrapper">
            <HizmetlerimizPage 
              onBack={() => {
                setCurrentPage('home');
                window.scrollTo(0, 0);
              }}
              onServiceSelect={(id) => {
                setCurrentPage(id);
                window.scrollTo(0, 0);
              }}
            />
          </motion.div>
        ) : currentPage === 'personel' ? (
          <motion.div key="personel" className="main-content-wrapper">
            <PersonelTasimaciligi 
              onBack={() => {
                setCurrentPage('home');
                window.scrollTo(0, 0);
              }} 
              onQuoteClick={() => {
                setCurrentPage('iletisim');
                window.scrollTo(0, 0);
              }}
            />
          </motion.div>
        ) : currentPage === 'ogrenci' ? (
          <motion.div key="ogrenci" className="main-content-wrapper">
            <OgrenciTasimaciligi 
              onBack={() => {
                setCurrentPage('home');
                window.scrollTo(0, 0);
              }} 
              onQuoteClick={() => {
                setCurrentPage('iletisim');
                window.scrollTo(0, 0);
              }}
            />
          </motion.div>
        ) : currentPage === 'turizm' ? (
          <motion.div key="turizm" className="main-content-wrapper">
            <TurizmTasimaciligi 
              onBack={() => { setCurrentPage('home'); window.scrollTo(0, 0); }} 
              onQuoteClick={() => { setCurrentPage('iletisim'); window.scrollTo(0, 0); }}
            />
          </motion.div>
        ) : currentPage === 'vip' ? (
          <motion.div key="vip" className="main-content-wrapper">
            <VipTransfer 
              onBack={() => { setCurrentPage('home'); window.scrollTo(0, 0); }} 
              onQuoteClick={() => { setCurrentPage('iletisim'); window.scrollTo(0, 0); }}
            />
          </motion.div>
        ) : currentPage === 'kiralama' ? (
          <motion.div key="kiralama" className="main-content-wrapper">
            <AracKiralama 
              onBack={() => { setCurrentPage('home'); window.scrollTo(0, 0); }} 
              onQuoteClick={() => { setCurrentPage('iletisim'); window.scrollTo(0, 0); }}
            />
          </motion.div>
        ) : currentPage === 'filo-detay' ? (
          <motion.div key="filo-detay" className="main-content-wrapper">
            <FiloDetay 
              category={selectedFleetCategory}
              onBack={() => { setCurrentPage('home'); window.scrollTo(0, 0); }} 
              onQuoteClick={() => { setCurrentPage('iletisim'); window.scrollTo(0, 0); }}
            />
          </motion.div>
        ) : currentPage === 'iletisim' ? (
          <motion.div key="iletisim" className="main-content-wrapper">
            <IletisimPage onBack={() => {
              setCurrentPage('home');
              window.scrollTo(0, 0);
            }} />
          </motion.div>
        ) : currentPage === 'filomuz' ? (
          <motion.div key="filomuz" className="main-content-wrapper">
            <FilomuzPage 
              onBack={() => { setCurrentPage('home'); window.scrollTo(0, 0); }} 
              onQuoteClick={() => { setCurrentPage('iletisim'); window.scrollTo(0, 0); }}
            />
          </motion.div>
        ) : null}
      </AnimatePresence>

      {/* Premium Footer */}
      <footer style={{ 
        background: 'linear-gradient(135deg, #0a192f 0%, #072e5a 100%)', 
        color: 'white',
        padding: '80px 5% 40px',
        position: 'relative',
        overflow: 'hidden'
      }}>
        {/* Decorative elements */}
        <div style={{ position: 'absolute', top: 0, right: '10%', width: '300px', height: '300px', background: 'radial-gradient(circle, rgba(226,27,27,0.15) 0%, transparent 70%)', borderRadius: '50%' }}></div>
        <div style={{ position: 'absolute', bottom: '-50px', left: '-50px', width: '200px', height: '200px', background: 'radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%)', borderRadius: '50%' }}></div>

        <div style={{ maxWidth: '1200px', margin: '0 auto', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '50px', marginBottom: '60px', position: 'relative', zIndex: 10 }}>
          
          {/* Brand Col */}
          <div className="footer-brand-col">
            <div style={{ marginBottom: '25px', display: 'inline-flex', flexDirection: 'column', alignItems: 'center' }}>
              <motion.h2 
                animate={{ backgroundPosition: ['200% center', '0% center'] }}
                transition={{ repeat: Infinity, duration: 4, ease: "linear" }}
                style={{ 
                  fontSize: '2.2rem', 
                  fontWeight: 900, 
                  fontFamily: 'var(--font-heading)', 
                  margin: 0,
                  background: 'linear-gradient(to right, #ffffff 20%, #e21b1b 50%, #ffffff 80%)',
                  backgroundSize: '200% auto',
                  WebkitBackgroundClip: 'text',
                  WebkitTextFillColor: 'transparent',
                  display: 'block',
                  letterSpacing: '-1px'
                }}>
                MEHMET ÇELEBİ
              </motion.h2>
              <h3 style={{ 
                fontSize: '1.3rem', 
                fontWeight: 600, 
                letterSpacing: '8px', 
                color: 'rgba(255,255,255,0.8)', 
                margin: 0, 
                marginTop: '4px',
                marginLeft: '8px'
              }}>
                TURİZM
              </h3>
            </div>
            <p style={{ color: 'rgba(255,255,255,0.7)', fontSize: '1rem', lineHeight: 1.8 }}>
              Teknoloji destekli yeni nesil taşımacılık çözümleri. Lüks, güven ve konforu yılların tecrübesiyle birleştiriyoruz.
            </p>
          </div>


          {/* Contact Col */}
          <div className="footer-contact-col">
            <h4 style={{ fontSize: '1.2rem', fontWeight: 700, marginBottom: '25px', letterSpacing: '1px' }}>İLETİŞİM</h4>
            <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '20px', padding: 0, margin: 0 }}>
              <li className="footer-contact-item" style={{ display: 'flex', gap: '15px', alignItems: 'flex-start' }}>
                <Phone size={20} color="var(--color-accent-secondary)" style={{ marginTop: '2px' }} />
                <span style={{ color: 'rgba(255,255,255,0.9)', fontSize: '1rem', fontWeight: 600 }}>+90 532 473 35 64</span>
              </li>
              <li className="footer-contact-item" style={{ display: 'flex', gap: '15px', alignItems: 'flex-start' }}>
                <Mail size={20} color="var(--color-accent-secondary)" style={{ marginTop: '2px' }} />
                <span style={{ color: 'rgba(255,255,255,0.7)', fontSize: '1rem' }}>info@mehmetcelebiturizm.com</span>
              </li>
              <li className="footer-contact-item" style={{ display: 'flex', gap: '15px', alignItems: 'flex-start' }}>
                <MapPin size={24} color="var(--color-accent-secondary)" style={{ marginTop: '2px', flexShrink: 0 }} />
                <span style={{ color: 'rgba(255,255,255,0.7)', fontSize: '1rem', lineHeight: 1.6 }}>Fevziçakmak Mah. 10591. Sk No:26/A<br/>Karatay - KONYA</span>
              </li>
            </ul>
          </div>
        </div>

        <div style={{ 
          maxWidth: '1200px', margin: '0 auto', textAlign: 'center', 
          color: 'rgba(255,255,255,0.5)', fontSize: '0.9rem', 
          borderTop: '1px solid rgba(255,255,255,0.1)', paddingTop: '30px',
          position: 'relative', zIndex: 10,
          display: 'flex', flexDirection: 'column', gap: '8px'
        }}>
          <span>© 2026 Mehmet Çelebi Turizm. Tüm hakları saklıdır.</span>
          <span style={{ fontSize: '0.8rem', color: 'rgba(255,255,255,0.4)', letterSpacing: '0.5px' }}>
            Yazılım: <strong style={{ color: 'rgba(255,255,255,0.7)', fontWeight: 800 }}>Sabri DOĞRU</strong> | Tasarım: <strong style={{ color: 'rgba(255,255,255,0.7)', fontWeight: 800 }}>Mehmet ÇELEBİ</strong>
          </span>
        </div>
      </footer>

      {/* Global WhatsApp Button */}
      <WhatsAppButton />

      <style dangerouslySetInnerHTML={{__html: `
        @media (max-width: 768px) {
          .desktop-menu { display: none !important; }
          .mobile-toggle { display: block !important; }
          
          .footer-brand-col { text-align: center !important; display: flex; flex-direction: column; align-items: center; }
          .footer-brand-col p { text-align: center !important; }
          .footer-links-col { display: none !important; }
          .footer-contact-col { text-align: center !important; }
          .footer-contact-item { justify-content: center !important; text-align: center !important; flex-direction: column !important; align-items: center !important; gap: 8px !important; }
          .footer-contact-item svg { margin-top: 0 !important; }
        }
      `}} />
    </div>
  );
}

export default App;
