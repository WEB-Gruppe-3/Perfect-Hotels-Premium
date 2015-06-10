-- Eksamen i IS-WEB1000 V�r 2015, Gruppe 3.
-- Dummy data for Perfect Hotels Premium

-- Image
INSERT INTO Image(URL, Description) 
VALUES('img/hotels/hotel-01.jpg', 'Hotell 1.');

INSERT INTO Image(URL, Description) 
VALUES('img/hotels/hotel-02.jpg', 'Hotell 2.');

INSERT INTO Image(URL, Description) 
VALUES('img/hotels/hotel-03.jpg', 'Hotell 3.');

-- RoomType
INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Vanlig', '2', '1000', '1', 'Et helt vanlig hotellrom.');

INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Premium', '4', '2000', '2', 'Et premium hotellrom.');

INSERT INTO RoomType(Name, NumOfBeds, Price, ImageID, Description)
VALUES('Suite', '6', '5000', '3', 'En suite av ytterste eksklusivitet.');

-- Hotel
INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Hotell X', '1', 'Beskrivelse av Hotell X');

INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Hotell Y', '2', 'Beskrivelse av Hotell Y');

INSERT INTO Hotel(Name, ImageID, Description)
VALUES('Hotell Z', '3', 'Beskrivelse av Hotell Z.');

-- HotelRoomType
INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '1');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '2');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('1', '3');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '1');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '2');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('2', '3');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '1');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '2');

INSERT INTO HotelRoomType(RoomTypeID, HotelID)
VALUES('3', '3');

-- Room
INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '1');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '2');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('101', '3');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('102', '3');

INSERT INTO Room(RoomNumber, HotelRoomTypeID)
VALUES('103', '3');

-- CustomerOrder
-- Should not insert orders

-- Booking
-- Should not insert bookings

-- MaintenanceUser
INSERT INTO MaintenanceUser(UserName, Password)
VALUES('admin', 'admin');