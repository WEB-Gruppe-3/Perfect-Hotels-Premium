-- ######################## CREATE ########################
-- Eksamen i IS-WEB1000 Vår 2015, Gruppe 3.
-- Tabellstruktur for Perfect Hotels Premium

-- Image
CREATE TABLE IF NOT EXISTS Image (
  ID INT NOT NULL AUTO_INCREMENT,
  URL VARCHAR(300) NOT NULL,
  Description VARCHAR(300),

  PRIMARY KEY(ID)
);

-- RoomType
CREATE TABLE IF NOT EXISTS RoomType (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(45) NOT NULL,
  NumOfBeds INT NOT NULL,
  Price INT NOT NULL,
  ImageID INT NOT NULL,
  Description VARCHAR(500) NOT NULL,

  PRIMARY KEY(ID),
  FOREIGN KEY(ImageID) REFERENCES Image(ID)
);

-- Hotel
CREATE TABLE IF NOT EXISTS Hotel (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(45) NOT NULL,
  ImageID INT NOT NULL,
  Description VARCHAR(500) NOT NULL,

  PRIMARY KEY(ID),
  FOREIGN KEY(ImageID) REFERENCES Image(ID)
);

-- HotelRoomType
CREATE TABLE IF NOT EXISTS HotelRoomType (
  ID INT NOT NULL AUTO_INCREMENT,
  RoomTypeID INT NOT NULL,
  HotelID INT NOT NULL,

  PRIMARY KEY(ID),
  FOREIGN KEY(RoomTypeID) REFERENCES RoomType(ID),
  FOREIGN KEY(HotelID) REFERENCES Hotel(ID)
);

-- Room
CREATE TABLE IF NOT EXISTS Room (
  ID INT NOT NULL AUTO_INCREMENT,
  RoomNumber INT NOT NULL,
  HotelRoomTypeID INT NOT NULL,

  PRIMARY KEY(ID),
  FOREIGN KEY(HotelRoomTypeID) REFERENCES HotelRoomType(ID)
);

-- CustomerOrder
CREATE TABLE IF NOT EXISTS CustomerOrder (
  ID INT NOT NULL AUTO_INCREMENT,
  Reference VARCHAR(100) NOT NULL,

  PRIMARY KEY(ID)
);

-- Booking
CREATE TABLE IF NOT EXISTS Booking (
  ID INT NOT NULL AUTO_INCREMENT,
  FromDate DATE NOT NULL,
  ToDate DATE NOT NULL,
  RoomID INT DEFAULT '0',
  HotelRoomTypeID INT NOT NULL,
  CustomerOrderID INT NOT NULL,

  PRIMARY KEY(ID),
  FOREIGN KEY(HotelRoomTypeID) REFERENCES HotelRoomType(ID),
  FOREIGN KEY(CustomerOrderID) REFERENCES CustomerOrder(ID)
);

-- MaintenanceUser
CREATE TABLE IF NOT EXISTS MaintenanceUser (
  ID INT NOT NULL AUTO_INCREMENT,
  UserName VARCHAR(45) NOT NULL,
  Password VARCHAR(45) NOT NULL,

  PRIMARY KEY(ID)
);



-- ######################## INSERT ########################
-- Eksamen i IS-WEB1000 V�r 2015, Gruppe 3.
-- Dummy data for Perfect Hotels Premium

-- Image
-- Inserting hotel images
INSERT INTO Image(URL, Description)
VALUES('img/hotels/hotel_miramas.jpg', 'Bilde av Hotel Miramas.');

INSERT INTO Image(URL, Description)
VALUES('img/hotels/kings_lagoon_resort.jpg', 'Bilde av Kings Lagoon Resort.');

INSERT INTO Image(URL, Description)
VALUES('img/hotels/luxury_coast_resort.jpg', 'Bilde av Luxury Coast Resort.');

INSERT INTO Image(URL, Description)
VALUES('img/hotels/palacio_del_mar.jpg', 'Bilde av Palacio Del Mar.');

INSERT INTO Image(URL, Description)
VALUES('img/hotels/royal_park_hotel.jpg', 'Bilde av Royal Park Hotel.');

-- Inserting room type images
INSERT INTO Image(URL, Description)
VALUES('img/rooms/standard.jpg', 'Bilde av romtypen Standard.');

INSERT INTO Image(URL, Description)
VALUES('img/rooms/family.jpg', 'Bilde av romtypen Family.');

INSERT INTO Image(URL, Description)
VALUES('img/rooms/deluxe.jpg', 'Bilde av romtypen Deluxe.');

INSERT INTO Image(URL, Description)
VALUES('img/rooms/executive_suite.jpg', 'Bilde av romtypen Executive Suite.');

-- RoomType
INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Standard', '2', '950', '6', 'Beskrivelse av Standard goes here.');

INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Family', '4', '1940', '7', 'Beskrivelse av Family goes here.');

INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Deluxe', '4', '4400', '8', 'Beskrivelse av romtype Deluxe goes here.');

INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Executive Suite', '4', '8400', '9', 'Beskrivelse av romtype Executive Suite goes here.');

-- Hotel
INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Hotel Miramas', '1', 'Hotel Miramas ligger vakkert plassert ved sydhavets solfyllte kyst. Gjestene kan forvendte servering av nydelig sydhavsmat og god vin. Kort gangavstand til både strand og nærliggende tettsted.');

INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Kings Lagoon Resort', '2', 'Beskrivelse av Kings Lagoon Resort');

INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Luxury Coast Resort', '3', 'Beskrivelse av Luxury Coast Resort.');

INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Palacio Del Mar', '4', 'Beskrivelse av Palacio Del Mar.');

INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Royal Park Hotel', '5', 'Beskrivelse av Royal Park Hotel.');

-- HotelRoomType
-- Hotell ID 1
INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '1');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '1');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '1');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('4', '1');

-- Hotell ID 2
INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '2');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '2');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '2');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('4', '2');

-- Hotell ID 3
INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '3');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '3');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '3');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('4', '3');

-- Hotell ID 4
INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '4');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '4');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '4');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('4', '4');

-- Hotell ID 5
INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '5');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '5');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '5');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('4', '5');

-- Room
-- Adding 5 rooms per HotelRoomTypeID (there are 20 hotellroomtypeIDs in total)
-- HotelRoomType ID 1
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '1');

-- HotelRoomType ID 2
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '2');

-- HotelRoomType ID 3
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '3');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '3');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '3');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '3');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '3');

-- HotelRoomType ID 4
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '4');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '4');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '4');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '4');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '4');

-- HotelRoomType ID 5
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '5');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '5');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '5');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '5');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '5');

-- HotelRoomType ID 6
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '6');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '6');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '6');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '6');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '6');

-- HotelRoomType ID 7
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '7');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '7');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '7');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '7');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '7');

-- HotelRoomType ID 8
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '8');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '8');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '8');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '8');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '8');

-- HotelRoomType ID 9
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '9');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '9');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '9');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '9');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '9');

-- HotelRoomType ID 10
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '10');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '10');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '10');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '10');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '10');

-- HotelRoomType ID 11
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '11');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '11');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '11');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '11');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '11');

-- HotelRoomType ID 12
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '12');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '12');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '12');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '12');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '12');

-- HotelRoomType ID 13
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '13');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '13');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '13');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '13');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '13');

-- HotelRoomType ID 14
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '14');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '14');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '14');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '14');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '14');

-- HotelRoomType ID 15
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '15');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '15');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '15');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '15');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '15');

-- HotelRoomType ID 16
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '16');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '16');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '16');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '16');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '16');

-- HotelRoomType ID 17
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '17');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '17');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '17');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '17');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '17');

-- HotelRoomType ID 18
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '18');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '18');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '18');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '18');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '18');

-- HotelRoomType ID 19
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '19');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '19');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '19');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '19');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '19');

-- HotelRoomType ID 20
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '20');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '20');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '20');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('104', '20');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('105', '20');

-- CustomerOrder
-- Should not insert orders

-- Booking
-- Should not insert bookings

-- MaintenanceUser
-- There should only be 1 user in this table.
INSERT INTO MaintenanceUser(UserName, Password)
VALUES('admin', 'admin');