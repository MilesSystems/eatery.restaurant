<?php
#phpinfo() and exit;

const DS = DIRECTORY_SEPARATOR; // All folder constants end in a trailing slash /

define('SERVER_ROOT', __DIR__ . DS);  // Set our root folder for the application

const APP_ROOT = SERVER_ROOT;         // I would like to change to only using app_root soon

if (false === (include SERVER_ROOT . 'Data/Vendors/autoload.php')) {     // Load the autoload() for composer dependencies located in the Services folder
    print '<h1>Loading Composer Failed. See Carbonphp.com for documentation.</h1>' and die;     // Composer autoload
}



$v =  [
    '0' => 'Marilyn Monroe',
    '1' => 'Abraham Lincoln',
    '2' => 'Mother Teresa',
    '3' => 'John F. Kennedy',
    '4' => 'Martin Luther King',
    '5' => 'Nelson Mandela',
    '6' => 'Winston Churchill',
    '7' => 'Bill Gates',
    '8' => 'Muhammad Ali',
    '9' => 'Mahatma Gandhi',
    '10' => 'Margaret Thatcher',
    '11' => 'Charles de Gaulle',
    '12' => 'Christopher Columbus',
    '13' => 'George Orwell',
    '14' => 'Charles Darwin',
    '15' => 'Elvis Presley',
    '16' => 'Albert Einstein',
    '17' => 'Paul McCartney',
    '18' => 'Plato',
    '19' => 'Queen Elizabeth II',
    '20' => 'Queen Victoria',
    '21' => 'John M Keynes',
    '22' => 'Mikhail Gorbachev',
    '23' => 'Jawaharlal Nehru',
    '24' => 'Leonardo da Vinci',
    '25' => 'Louis Pasteur',
    '26' => 'Leo Tolstoy',
    '27' => 'Pablo Picasso',
    '28' => 'Vincent Van Gogh',
    '29' => 'Franklin D. Roosevelt',
    '30' => 'Pope John Paul II',
    '31' => 'Thomas Edison',
    '32' => 'Rosa Parks',
    '33' => 'Aung San Suu Kyi',
    '34' => 'Lyndon Johnson',
    '35' => 'Ludwig Beethoven',
    '36' => 'Oprah Winfrey',
    '37' => 'Indira Gandhi',
    '38' => 'Eva Peron',
    '39' => 'Benazir Bhutto',
    '40' => 'Desmond Tutu',
    '41' => 'Dalai Lama',
    '42' => 'Walt Disney',
    '43' => 'Neil Armstrong',
    '44' => 'Donald Trump',
    '45' => 'Peter Sellers',
    '46' => 'Barack Obama',
    '47' => 'Malcolm X',
    '48' => 'J.K.Rowling',
    '49' => 'Richard Branson',
    '50' => 'Pele',
    '51' => 'Angelina Jolie',
    '52' => 'Jesse Owens',
    '53' => 'Ernest Hemingway',
    '54' => 'John Lennon',
    '55' => 'Henry Ford',
    '56' => 'Haile Selassie',
    '57' => 'Joseph Stalin',
    '58' => 'Lord Baden Powell',
    '59' => 'Michael Jordon',
    '60' => 'George Bush Jnr',
    '61' => 'Vladimir Lenin',
    '62' => 'Ingrid Bergman',
    '63' => 'Fidel Castro',
    '64' => 'Oscar Wilde',
    '65' => 'Coco Chanel',
    '66' => 'Pope Francis',
    '67' => 'Amelia Earhart',
    '68' => 'Adolf Hitler',
    '69' => 'Sting',
    '70' => 'Mary Magdalene',
    '71' => 'Alfred Hitchcock',
    '72' => 'Michael Jackson',
    '73' => 'Madonna',
    '74' => 'Mata Hari',
    '75' => 'Cleopatra',
    '76' => 'Grace Kelly',
    '77' => 'Steve Jobs',
    '78' => 'Ronald Reagan',
    '79' => 'Lionel Messi',
    '80' => 'Babe Ruth',
    '81' => 'Bob Geldof',
    '82' => 'Leon Trotsky',
    '83' => 'Roger Federer',
    '84' => 'Sigmund Freud',
    '85' => 'Woodrow Wilson',
    '86' => 'Mao Zedong',
    '87' => 'Katherine Hepburn',
    '88' => 'Audrey Hepburn',
    '89' => 'David Beckham',
    '90' => 'Tiger Woods',
    '91' => 'Usain Bolt',
    '92' => 'Carl Lewis',
    '93' => 'Prince Charles',
    '94' => 'Jacqueline Kennedy Onassis',
    '95' => 'C.S. Lewis',
    '96' => 'Billie Holiday',
    '97' => 'J.R.R. Tolkien',
    '98' => 'Billie Jean King',
    '99' => 'Anne Frank'
];





// $app = new Carbon\Carbon('Application/Config/Config.php');

/** At one point I returned the invocation of $app to show that
 * the application will not exit on completion, but rather return
 * back to this index file. This means you can still execute code
 * after $app(); I stopped returning the __invoke() because if false
 * is returned, the index will re-execute. This turns very bad quickly.
 */

// $app();


return true;


