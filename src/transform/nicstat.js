function solar_transform_nicstat(elem, cmd, rc, out) {
	var tbl = new TableTransformer()
		
	tbl.header("Time", "Time")
	tbl.header("Int", "Interface")
	tbl.header("rKb/s", "read Kbytes/s")
	tbl.header("wKb/s", "write Kbytes/s")
	tbl.header("rPk/s", "read Packets/s")
	tbl.header("wPk/s", "write Packets/s")
	tbl.header("rAvs", "read Average size, bytes")
	tbl.header("wAvs", "write Average size, bytes")
	tbl.header("%Util", "%Utilisation (r+w/ifspeed)")
	tbl.header("Sat", "Saturation (defer, nocanput, norecvbuf, noxmtbuf)")
	
	tbl.create(out, elem)
	
	return "Table"
}