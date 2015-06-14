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
VALUES('Hotel Miramas', '1', 'Beskrivelse av Hotel Miramas');

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
-- Adding 5 rooms per HotelRoomTypeID
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


-- CustomerOrder
-- Should not insert orders

-- Booking
-- Should not insert bookings

-- MaintenanceUser
-- There should only be 1 user in this table.
INSERT INTO MaintenanceUser(UserName, Password)
VALUES('admin', 'admin');