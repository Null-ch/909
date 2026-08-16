<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ваш аккаунт</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; background: #f6f8f6; margin: 0; padding: 24px;">
    <div style="max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 32px;">
        <h1 style="font-size: 20px; color: #1b4d1b; margin-top: 0;">Добро пожаловать, {{ $user->name }}!</h1>

        <p>Мы создали для вас личный кабинет на {{ setting('shop_name', config('app.name')) }}, чтобы вы могли отслеживать свои заказы.</p>

        <table style="width: 100%; background: #f6f8f6; border-radius: 8px; padding: 16px; margin: 24px 0;">
            <tr>
                <td style="padding: 8px 16px; color: #666;">Email</td>
                <td style="padding: 8px 16px; font-weight: bold;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 16px; color: #666;">Пароль</td>
                <td style="padding: 8px 16px; font-weight: bold;">{{ $password }}</td>
            </tr>
        </table>

        <p>
            <a href="{{ url('/login') }}" style="display: inline-block; background: #2d7a2d; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px;">
                Войти в личный кабинет
            </a>
        </p>

        <p style="color: #666; font-size: 14px;">
            Рекомендуем сменить пароль после входа в разделе «Личный кабинет → Профиль».
        </p>
    </div>
</body>
</html>
