-- Eksamen i IS-WEB1000 V�r 2015, Gruppe 3.
-- Dummy data for Perfect Hotels Premium

-- Image
INSERT INTO Image(URL, Description) 
VALUES('http://bildr.no/image/NFY0ZjZr.jpeg', 'Prinsippskisse av forsiden.');

INSERT INTO Image(URL, Description) 
VALUES('http://bildr.no/image/RGNHOTcv.jpeg', 'Prinsippskisse av bestill siden.');

INSERT INTO Image(URL, Description) 
VALUES('http://bildr.no/image/eElhQk0w.jpeg', 'Prinsippskisse av sjekk-inn siden.');

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
INSERT INTO CustomerOrder(Reference)
VALUES('1');

INSERT INTO CustomerOrder(Reference)
VALUES('2');

INSERT INTO CustomerOrder(Reference)
VALUES('3');

-- Booking
INSERT INTO Booking(FromDate, ToDate, RoomID, HotelRoomTypeID, CustomerOrderID)
VALUES('2015-01-01 11:55:56', '2015-01-03 10:00:00', '1', '1', '1');

INSERT INTO Booking(FromDate, ToDate, RoomID, HotelRoomTypeID, CustomerOrderID)
VALUES('2015-02-05 10:00:00', '2015-02-07 10:00:00', '2', '2', '2');

INSERT INTO Booking(FromDate, ToDate, RoomID, HotelRoomTypeID, CustomerOrderID)
VALUES('2015-03-07 14:33:01', '2015-03-10 12:00:00', '3', '3', '3');

-- MaintenanceUser
INSERT INTO MaintenanceUser(UserName, Password)
VALUES('admin', 'admin');

INSERT INTO MaintenanceUser(UserName, Password)
VALUES('bjarne', 'dsjkhd@&sjk');

INSERT INTO MaintenanceUser(UserName, Password)
VALUES('ole', 'password123');