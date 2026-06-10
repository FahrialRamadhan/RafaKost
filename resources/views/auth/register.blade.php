<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-6">

        <div class="flex items-stretch gap-12 max-w-6xl w-full">

            <!-- LEFT -->
            <div class="hidden md:flex w-1/2">
                <div class="w-full h-full min-h-[700px] rounded-[24px] shadow-xl overflow-hidden relative">

                    <img src="/images/lorong.png"
                        class="absolute inset-0 w-full h-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-t from-white/50 via-black/50 to-transparent"></div>

                    <div class="relative z-10 p-6 flex flex-col justify-between h-full">
                        <img src="/images/secondlogo.png" class="w-10 h-auto">

                        <h2 class="text-white text-3xl font-semibold leading-snug text-start p-5">
                            Tempat Tinggal Nyaman, Serasa di Rumah.
                        </h2>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full md:w-1/2 flex items-center">
                <div class="w-full max-w-md">

                    <!-- HEADER -->
                    <div class="mb-5">
                        <img src="/images/logo.png" class="w-[40px] mb-3">

                        <h1 class="text-2xl font-bold text-gray-900">
                            Register Account
                        </h1>

                        <p class="text-sm text-gray-500 mt-2">
                            Buat akun untuk mulai menggunakan Rafa Kost
                        </p>
                    </div>

                    <!-- FORM -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- NAME -->
                        <div>
                            <label class="text-sm text-gray-600">Full Name</label>
                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="mt-3">
                            <label class="text-sm text-gray-600">Email</label>
                            <input type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            <p id="email-message" class="hidden mt-1 text-xs"></p>

                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="mt-3">
                            <label class="text-sm text-gray-600">Password</label>
                            <input type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            @if ($errors->has('password'))
                                <div class="mt-2 text-xs text-red-600 space-y-1">
                                    @foreach ($errors->get('password') as $message)
                                        <p>{{ $message }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <!-- LIVE PASSWORD REQUIREMENTS -->
                            <div class="mt-2 space-y-1 text-xs">
                                <div id="rule-length" class="flex items-center gap-2 text-gray-500">
                                    <span class="rule-icon w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center text-[10px]">✓</span>
                                    <span>Minimal 8 karakter</span>
                                </div>

                                <div id="rule-uppercase" class="flex items-center gap-2 text-gray-500">
                                    <span class="rule-icon w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center text-[10px]">✓</span>
                                    <span>Mengandung huruf besar</span>
                                </div>

                                <div id="rule-lowercase" class="flex items-center gap-2 text-gray-500">
                                    <span class="rule-icon w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center text-[10px]">✓</span>
                                    <span>Mengandung huruf kecil</span>
                                </div>

                                <div id="rule-number" class="flex items-center gap-2 text-gray-500">
                                    <span class="rule-icon w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center text-[10px]">✓</span>
                                    <span>Mengandung angka</span>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mt-3">
                            <label class="text-sm text-gray-600">Confirm Password</label>
                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            <p id="confirm-message" class="hidden mt-1 text-xs"></p>
                        </div>

                        <!-- BUTTON -->
                        <button id="register-button"
                            class="w-full mt-5 bg-blue-500 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                            disabled>
                            Register
                        </button>

                        <!-- FOOTER -->
                        <p class="text-xs text-center text-gray-500 mt-5">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-blue-500 font-medium">Sign In</a>
                        </p>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const emailMessage = document.getElementById('email-message');

        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const registerButton = document.getElementById('register-button');
        const confirmMessage = document.getElementById('confirm-message');

        let emailAvailable = false;
        let emailCheckTimer = null;

        const rules = {
            length: document.getElementById('rule-length'),
            uppercase: document.getElementById('rule-uppercase'),
            lowercase: document.getElementById('rule-lowercase'),
            number: document.getElementById('rule-number'),
        };

        function setRuleState(element, valid) {
            const icon = element.querySelector('.rule-icon');

            if (valid) {
                element.classList.remove('text-gray-500');
                element.classList.add('text-green-600');

                icon.classList.remove('border-gray-300');
                icon.classList.add('border-green-500', 'bg-green-500', 'text-white');
            } else {
                element.classList.remove('text-green-600');
                element.classList.add('text-gray-500');

                icon.classList.remove('border-green-500', 'bg-green-500', 'text-white');
                icon.classList.add('border-gray-300');
            }
        }

        function validatePassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;

            const validLength = password.length >= 8;
            const validUppercase = /[A-Z]/.test(password);
            const validLowercase = /[a-z]/.test(password);
            const validNumber = /[0-9]/.test(password);

            setRuleState(rules.length, validLength);
            setRuleState(rules.uppercase, validUppercase);
            setRuleState(rules.lowercase, validLowercase);
            setRuleState(rules.number, validNumber);

            const passwordValid = validLength && validUppercase && validLowercase && validNumber;

            if (confirmPassword.length > 0) {
                confirmMessage.classList.remove('hidden');

                if (password === confirmPassword) {
                    confirmMessage.textContent = 'Konfirmasi password cocok.';
                    confirmMessage.classList.remove('text-red-600');
                    confirmMessage.classList.add('text-green-600');
                } else {
                    confirmMessage.textContent = 'Konfirmasi password tidak sama.';
                    confirmMessage.classList.remove('text-green-600');
                    confirmMessage.classList.add('text-red-600');
                }
            } else {
                confirmMessage.classList.add('hidden');
            }

            const confirmValid = password.length > 0 && password === confirmPassword;

            registerButton.disabled = !(passwordValid && confirmValid && emailAvailable);
        }

        function validateEmailFormat(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function setEmailMessage(message, type) {
            emailMessage.classList.remove('hidden');
            emailMessage.textContent = message;

            emailMessage.classList.remove('text-red-600', 'text-green-600', 'text-gray-500');

            if (type === 'success') {
                emailMessage.classList.add('text-green-600');
            } else if (type === 'error') {
                emailMessage.classList.add('text-red-600');
            } else {
                emailMessage.classList.add('text-gray-500');
            }
        }

        function checkEmailAvailability() {
            const email = emailInput.value.trim().toLowerCase();

            emailAvailable = false;
            validatePassword();

            if (email.length === 0) {
                emailMessage.classList.add('hidden');
                return;
            }

            if (!validateEmailFormat(email)) {
                setEmailMessage('Format email belum valid.', 'error');
                return;
            }

            setEmailMessage('Mengecek email...', 'neutral');

            fetch('{{ route('check-email') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    email: email,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    emailAvailable = false;
                    setEmailMessage('Email sudah terdaftar.', 'error');
                } else {
                    emailAvailable = true;
                    setEmailMessage('Email tersedia.', 'success');
                }

                validatePassword();
            })
            .catch(() => {
                emailAvailable = false;
                setEmailMessage('Gagal mengecek email. Coba lagi.', 'error');
                validatePassword();
            });
        }

        emailInput.addEventListener('input', function () {
            clearTimeout(emailCheckTimer);

            emailCheckTimer = setTimeout(() => {
                checkEmailAvailability();
            }, 500);
        });

        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);

        if (emailInput.value.trim().length > 0) {
            checkEmailAvailability();
        }

        validatePassword();
    </script>
</x-guest-layout>