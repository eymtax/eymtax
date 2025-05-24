// Chatbot functionality
document.addEventListener('DOMContentLoaded', function() {
    const chatWindow = document.getElementById('chatWindow');
    const chatMessages = document.getElementById('chatMessages');
    const userInput = document.getElementById('userInput');

    // Show welcome notification
    function showWelcomeNotification() {
        if (!localStorage.getItem('chatbotWelcomeShown')) {
            const notification = document.createElement('div');
            notification.className = 'chat-notification';
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-icon">💬</span>
                    <p>مرحباً! أنا مساعدك الافتراضي. كيف يمكنني مساعدتك اليوم؟</p>
                    <button onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;
            document.body.appendChild(notification);
            localStorage.setItem('chatbotWelcomeShown', 'true');
            
            // Remove notification after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }

    // Toggle chat window
    window.toggleChat = function() {
        chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex';
        if (chatWindow.style.display === 'flex') {
            chatMessages.scrollTop = chatMessages.scrollHeight;
            userInput.focus();
        }
    }

    // Send message
    window.sendMessage = function() {
        const message = userInput.value.trim();
        if (message) {
            // Add user message
            addMessage(message, 'user');
            userInput.value = '';

            // Simulate bot response
            setTimeout(() => {
                const response = getBotResponse(message);
                addMessage(response, 'bot');
            }, 1000);
        }
    }

    // Add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.innerHTML = `<p>${text}</p>`;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Get bot response
    function getBotResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        if (lowerMessage.includes('مرحبا') || lowerMessage.includes('اهلا') || lowerMessage.includes('السلام')) {
            return 'مرحباً بك في Eymta X! كيف يمكنني مساعدتك اليوم؟ يمكنني تقديم معلومات عن خدماتنا، الأسعار، أو أي استفسار آخر لديك.';
        } else if (lowerMessage.includes('خدمات') || lowerMessage.includes('ماذا تقدم')) {
            return 'نقدم مجموعة متنوعة من الخدمات المتكاملة:\n\n' +
                   '1. التسويق الإلكتروني: إدارة حسابات التواصل الاجتماعي، إعلانات ممولة، تحسين محركات البحث\n' +
                   '2. التصوير الاحترافي: تصوير المنتجات، تصوير الأعراس، تصوير العقارات\n' +
                   '3. خدمات الطباعة: بروشورات، فلايرات، بطاقات عمل، كتالوجات\n' +
                   '4. دليل الشركات: دليل شامل للشركات السورية في مختلف القطاعات\n\n' +
                   'هل ترغب في معرفة المزيد عن خدمة معينة؟';
        } else if (lowerMessage.includes('سعر') || lowerMessage.includes('تكلفة') || lowerMessage.includes('كم')) {
            return 'تختلف الأسعار حسب نوع الخدمة وحجم العمل. للحصول على عرض سعر دقيق، يمكنك التواصل معنا على الرقم 0938029294 وسنقدم لك عرضاً مفصلاً يتناسب مع احتياجاتك.';
        } else if (lowerMessage.includes('تواصل') || lowerMessage.includes('اتصال') || lowerMessage.includes('رقم')) {
            return 'يمكنك التواصل معنا عبر:\n\n' +
                   '📞 الهاتف: 0938029294\n' +
                   '📧 البريد الإلكتروني: info@eymtax.com\n' +
                   '📍 العنوان: سوريا\n' +
                   '⏰ ساعات العمل: من الساعة 9 صباحاً حتى 6 مساءً\n\n' +
                   'يمكنك أيضاً زيارة صفحة "تواصل معنا" في الموقع للمزيد من المعلومات.';
        } else if (lowerMessage.includes('دليل') || lowerMessage.includes('شركات')) {
            return 'دليل الشركات السورية يقدم:\n\n' +
                   '1. قائمة شاملة للشركات في مختلف القطاعات\n' +
                   '2. معلومات تفصيلية عن كل شركة\n' +
                   '3. إمكانية البحث والتصفية حسب القطاع\n' +
                   '4. إضافة شركتك مجاناً\n\n' +
                   'يمكنك زيارة صفحة دليل الشركات للبدء في البحث أو إضافة شركتك.';
        } else if (lowerMessage.includes('تصوير') || lowerMessage.includes('صور')) {
            return 'نقدم خدمات تصوير متكاملة:\n\n' +
                   '1. تصوير المنتجات: تصوير احترافي للمنتجات مع التركيز على التفاصيل\n' +
                   '2. تصوير الأعراس: تغطية كاملة لليوم الكبير\n' +
                   '3. تصوير العقارات: تصوير داخلي وخارجي للعقارات\n' +
                   '4. تصوير المناسبات: تصوير احترافي لجميع المناسبات\n\n' +
                   'هل ترغب في معرفة المزيد عن خدمة تصوير معينة؟';
        } else if (lowerMessage.includes('طباعة') || lowerMessage.includes('مطبوعات')) {
            return 'خدمات الطباعة لدينا تشمل:\n\n' +
                   '1. بروشورات وفلايرات\n' +
                   '2. بطاقات عمل\n' +
                   '3. كتالوجات ومجلات\n' +
                   '4. لافتات وبنرات\n' +
                   '5. أختام وطوابع\n\n' +
                   'جميع خدمات الطباعة تتم باستخدام أحدث التقنيات وأفضل المواد.';
        } else if (lowerMessage.includes('تسويق') || lowerMessage.includes('إعلان')) {
            return 'خدمات التسويق الإلكتروني لدينا تشمل:\n\n' +
                   '1. إدارة حسابات التواصل الاجتماعي\n' +
                   '2. إعلانات ممولة على منصات التواصل\n' +
                   '3. تحسين محركات البحث (SEO)\n' +
                   '4. تصميم الحملات الإعلانية\n' +
                   '5. تحليل الأداء والتقارير\n\n' +
                   'نقدم حلولاً تسويقية متكاملة لتعزيز حضورك الرقمي.';
        } else if (lowerMessage.includes('شكرا') || lowerMessage.includes('مشكور') || lowerMessage.includes('تمام')) {
            return 'شكراً لكم! يسعدنا دائماً مساعدتكم. إذا كان لديك أي استفسارات أخرى، لا تتردد في السؤال.';
        } else if (lowerMessage.includes('موقع') || lowerMessage.includes('ويب')) {
            return 'نقدم خدمات تصميم وتطوير المواقع الإلكترونية:\n\n' +
                   '1. تصميم مواقع احترافية\n' +
                   '2. تطوير مواقع متجاوبة\n' +
                   '3. تحسين محركات البحث\n' +
                   '4. صيانة وتحديث المواقع\n\n' +
                   'جميع المواقع التي نصممها تتميز بالجودة العالية والتجاوب مع جميع الأجهزة.';
        } else {
            return 'عذراً، لم أفهم سؤالك بشكل كامل. يمكنني مساعدتك في:\n\n' +
                   '1. معلومات عن خدماتنا\n' +
                   '2. الأسعار والعروض\n' +
                   '3. طرق التواصل معنا\n' +
                   '4. دليل الشركات\n' +
                   '5. خدمات التصوير والطباعة\n\n' +
                   'هل يمكنك إعادة صياغة سؤالك بطريقة أخرى؟';
        }
    }

    // Handle Enter key press
    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Show welcome notification
    showWelcomeNotification();
}); 