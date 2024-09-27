# Foosball Manager

**Foosball Manager** is a application designed to help you manage your foosball games effortlessly. Whether you’re playing casual single matches or engaging in competitive multi-round tournaments, this app has you covered.

## Features
- **Game Modes**: Choose from single matches, best-of-three, or best-of-five formats.
- **Match Tracking**: Record scores and monitor the progress of each game.
- **Team Management**: Easily add and manage players.
- **Match History**: Maintain a comprehensive log of all past games and their outcomes.

## Installation

To install the application, run the following commands:

```bash
git clone https://github.com/emestri/foosball-manager.git
cd foosball-manager
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

## Testing

To test the application, use the following command:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

You can find sample requests in the `.requests` folder.

## To Do
- Add API documentation
- Implement statistics
- Develop the frontend
